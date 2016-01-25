<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;

class SetIdentityInRequest
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * Constructor
     * @param AuthenticationServiceInterface $authService
     */
    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(
        ServerRequestInterface $req,
        ResponseInterface $res,
        callable $next
    ) {
        if ($this->authService->hasIdentity()) {
            $req = $req->withAttribute('identity', $this->authService->getIdentity());
        }

        return $next($req, $res);
    }
}
