<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization\Middleware;

use GrEduLabs\Authorization\RoleAwareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RedBeanPHP\R;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl\AclInterface;


class RoleProvider
{
    private $authService;

    private $acl;

    public function __construct(AuthenticationService $authService, AclInterface $acl)
    {
        $this->authService = $authService;
        $this->acl         = $acl;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $res      = $next($req, $res);
        $identity = $this->authService->getIdentity();

        if ($identity && $identity instanceof RoleAwareInterface) {
            $user       = R::load('user', $identity->id);
            $role       = ($user && isset($user->role)) ? $user->role : 'user';
            $validRoles = $this->acl->getRoles();
            $role       = (in_array($role, $validRoles)) ? $role : 'user';
            $identity->setRole($role);
            $this->authService->getStorage()->write($identity);
        }

        return $res;
    }
}
