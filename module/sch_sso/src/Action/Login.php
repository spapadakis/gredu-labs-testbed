<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSSO\Action;

use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Http\Response;
use Zend\Authentication\AuthenticationServiceInterface;

class Login
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
    protected $loginUrl;


    /**
     * Constructor.
     *
     * @param Twig                  $view
     * @param AuthenticationServiceInterface $authService
     * @param AdapterInterface      $authAdapter
     * @param Messages              $flash
     */
    public function __construct(
        AuthenticationServiceInterface $authService,
        Messages $flash,
        $successUrl,
        $loginUrl
    ) {
        $this->authService = $authService;
        $this->flash       = $flash;
        $this->loginUrl    = $loginUrl;
        $this->successUrl  = $successUrl;
    }

    public function __invoke(Request $req, Response $res)
    {
        $result =  $this->authService->authenticate();

        if (!$result->isValid()) {
            $this->flash->addMessage('danger', reset($result->getMessages()));

            return $res->withRedirect($this->loginUrl);
        }

        return $res->withRedirect($this->successUrl);
    }
}
