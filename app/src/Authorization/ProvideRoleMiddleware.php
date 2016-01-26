<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationService;

class ProvideRoleMiddleware
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $req, ResponseInterface $res, callable $next)
    {
        $res = $next($req, $res);
        if (null !== ($identity = $this->authService->getIdentity())
            && $identity instanceof RoleAwareInterface) {
            if (null === $identity->getRole()) {
                $identity->setRole('user');
                $this->authService->getStorage()->write($identity);
            }
        }

        return $res;
    }
}
