<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Adapter;

use GrEduLabs\Authentication\Identity;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

class EventsAdapter extends AbstractAdapter
{
    protected $events;

    public function __construct(callable $events)
    {
        $this->events = $events;
    }

    public function authenticate()
    {
        $events = $this->events;
        $result = $events('trigger', 'authenticate', $this->getIdentity(), $this->getCredential());

        $last = end($result['results']);
        if ($last instanceof Identity) {
            $events('trigger', 'authenticate.success', $last);
            
            return new Result(Result::SUCCESS, $last, ['Authentication success']);
        }
        $events('trigger', 'authenticate.failure');

        return new Result(Result::FAILURE_UNCATEGORIZED, null, ['Authentication failure']);
    }
}