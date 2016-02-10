<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization\Listener;

use GrEduLabs\Authorization\RoleAwareInterface;
use RedBeanPHP\R;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Permissions\Acl\AclInterface;

class RoleProvider
{
    private $session;

    private $acl;

    public function __construct(StorageInterface $session, AclInterface $acl)
    {
        $this->session = $session;
        $this->acl     = $acl;
    }

    public function __invoke(callable $stop, RoleAwareInterface $identity)
    {
        $user       = R::findOne('user', 'mail = ?', [$identity->mail]);
        $role       = ($user && isset($user->role)) ? $user->role : 'user';
        $validRoles = $this->acl->getRoles();
        $role       = (in_array($role, $validRoles)) ? $role : 'user';
        $identity->setRole($role);
        $this->session->write($identity);
    }
}
