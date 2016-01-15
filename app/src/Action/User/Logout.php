<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;

class Logout
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var string
     */
    protected $redirectUrl;

    public function __construct(
        AuthenticationServiceInterface $authService,
        $redirectUrl
    ) {
        $this->authService = $authService;
        $this->router      = $router;
        $this->redirectUrl = $redirectUrl;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, array $args = [])
    {
        if ($this->authService->hasIdentity()) {
            $this->authService->clearIdentity();
        }

        return $res->withRedirect($this->redirectUrl);
    }
}
