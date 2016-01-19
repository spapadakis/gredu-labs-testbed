<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Middleware;

use GrEduLabs\Authentication\Adapter\Cas;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;

class CasLogout
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var Cas
     */
    protected $adapter;

    public function __construct(AuthenticationServiceInterface $authService, Cas $adapter)
    {
        $this->authService = $authService;
        $this->adapter     = $adapter;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $next)
    {
        if ($this->authService->hasIdentity()) {
            $identity = $this->authService->getIdentity();
        }

        $res = $next($req, $res);
        if (isset($identity) && 'CAS' === $identity->authenticationSource) {
            $this->adapter->logout($req->getUri());
        }

        return $res;
    }
}
