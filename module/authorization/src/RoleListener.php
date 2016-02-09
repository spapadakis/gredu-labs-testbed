<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization;

use Zend\Authentication\Storage\StorageInterface;

class RoleListener
{
    private $session;

    public function __construct(StorageInterface $session)
    {
        $this->session = $session;
    }

    public function __invoke(callable $stop, RoleAwareInterface $identity)
    {
        $identity->setRole('user');
        $this->session->write($identity);
    }
}
