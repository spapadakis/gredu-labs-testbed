<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Action\User;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
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

    /**
     * @var callable
     */
    protected $events;

    public function __construct(
        AuthenticationServiceInterface $authService,
        callable $events,
        $redirectUrl
    ) {
        $this->authService = $authService;
        $this->events      = $events;
        $this->redirectUrl = $redirectUrl;
    }

    public function __invoke(ServerRequestInterface $req, Response $res)
    {
        if ($this->authService->hasIdentity()) {
            $identity = $this->authService->getIdentity();
            $events   = $this->events;
            $this->authService->clearIdentity();
            $events('trigger', 'logout', $identity, $this->redirectUrl);
        }

        return $res->withRedirect($this->redirectUrl);
    }
}
