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
use Slim\Views\Twig;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;

class Login
{
    /**
     * @var Twig
     */
    protected $view;

    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var AdapterInterface
     */
    protected $authAdapter;

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * Constructor
     * @param Twig $view
     * @param AuthenticationServiceInterface $authService
     * @param AdapterInterface $authAdapter
     * @param Messages $flash
     */
    public function __construct(
        Twig $view,
        AuthenticationServiceInterface $authService,
        AdapterInterface $authAdapter,
        Messages $flash
    ) {
        $this->view        = $view;
        $this->authService = $authService;
        $this->authAdapter = $authAdapter;
        $this->flash       = $flash;
        $this->authService->setAdapter($this->authAdapter);
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, array $args = [])
    {
        if ($req->isPost()) {
            if ($this->authAdapter instanceof ValidatableAdapterInterface) {
                $this->authAdapter->setIdentity($req->getParam('identity'))
                    ->setCredential($req->getParam('credential'));
            }
            $result = $this->authService->authenticate();
            if (!$result->isValid()) {
                $this->flash->addMessage('danger', reset($result->getMessages()));

                return $res->withRedirect($req->getUri());
            }

            return $res->withRedirect('/');
        }

        return $this->view->render($res, 'user/login.twig');
    }
}
