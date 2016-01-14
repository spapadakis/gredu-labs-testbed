<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Adapter;

use Exception;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventsCapableInterface;

class Events extends AbstractAdapter implements EventsCapableInterface
{
    const EVENT_AUTH = 'authenticate';

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Construct adapter
     * 
     * @param EventManagerInterface $events
     */
    public function __construct(EventManagerInterface $events = null)
    {
        if (null !== $events) {
            $this->setEventManager($events);
        }
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    protected function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([__CLASS__, get_class($this)]);
        $this->events = $events;
    }

    public function authenticate()
    {
        try {
            $identity = $this->getEventManager()->triggerUntil(function ($identity) {
                return !!$identity;
            }, self::EVENT_AUTH, $this, [
                'identity'   => $this->getIdentity(),
                'credential' => $this->getCredential(),
            ])->last();
        } catch (Exception $e) {
            return new Result(Result::FAILURE_UNCATEGORIZED, null, [$e->getMessage()]);
        }

        if (!$identity) {
            return new Result(Result::FAILURE, null, ['Authentication failure']);
        }

        return new Result(Result::SUCCESS, $identity, ['Authentication success']);
    }
}
