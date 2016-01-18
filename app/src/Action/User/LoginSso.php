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
use Slim\Flash\Messages;
use Zend\Authentication\AuthenticationServiceInterface;

class LoginSso
{

    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var string
     */
    protected $failureUrl;

    /**
     * Constructor
     * @param AuthenticationServiceInterface $authService
     * @param Messages $flash
     */
    public function __construct(
        AuthenticationServiceInterface $authService,
        Messages $flash,
        $successUrl,
        $failureUrl
    ) {
        $this->authService = $authService;
        $this->flash       = $flash;
        $this->successUrl  = $successUrl;
        $this->failureUrl  = $failureUrl;
    }

    public function __invoke(
        ServerRequestInterface $req,
        ResponseInterface $res
    ) {
        $result = $this->authService->authenticate();
        if (!$result->isValid()) {
            $this->flash->addMessage('danger', reset($result->getMessages()));

            return $res->withRedirect($this->failureUrl);
        }

        return $res->withRedirect($this->successUrl);
    }
}
