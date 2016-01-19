<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Action\User;

use GrEduLabs\Action\User\Login;
use Slim\Csrf\Guard;
use Slim\Http\Response;
use Zend\Authentication\Result;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    private $response;

    private $action;

    private $view;

    private $authService;

    private $authAdapter;

    private $flash;

    private $guard;

    private $successUrl = '/some/success/url';

    private $guardStorage = [];

    protected function setUp()
    {
        $this->request  = $this->getMockBuilder('\\Slim\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $this->response = new Response();
        $this->view     = $this->getMockBuilder('\\Slim\\Views\\Twig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->authService = $this->getMock('\\Zend\\Authentication\\AuthenticationServiceInterface');
        $this->flash       = $this->getMock('\\Slim\\Flash\\Messages');
        $this->authAdapter = $this->getMock('\\Zend\\Authentication\\Adapter\\AdapterInterface');
        $this->guard       = new Guard('csrf', $this->guardStorage);
        $this->action      = new Login(
            $this->view,
            $this->authService,
            $this->authAdapter,
            $this->flash,
            $this->guard,
            $this->successUrl
        );
    }

    public function testConstructorSetsDependecies()
    {
        $this->assertAttributeSame($this->view, 'view', $this->action);
        $this->assertAttributeSame($this->authService, 'authService', $this->action);
        $this->assertAttributeSame($this->authAdapter, 'authAdapter', $this->action);
        $this->assertAttributeSame($this->flash, 'flash', $this->action);
        $this->assertAttributeSame($this->guard, 'csrf', $this->action);
        $this->assertAttributeSame($this->successUrl, 'successUrl', $this->action);
    }

    public function testInvokeSetsAdapterToService()
    {
        $adapter     = null;
        $authService = $this->getMock('\\Zend\\Authentication\\AuthenticationService');
        $authService->expects($this->any())
            ->method('setAdapter')
            ->will($this->returnCallback(function () use (&$adapter) {
                $args = func_get_args();
                $adapter = $args[0];
            }));

        $action = new Login(
            $this->view,
            $authService,
            $this->authAdapter,
            $this->flash,
            $this->guard,
            $this->successUrl
        );
        $this->assertSame($this->authAdapter, $adapter);
    }

    public function testInvokeReturnsViewOnGetRequest()
    {
        $template = null;
        $data     = [];

        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(false));

        $this->view->expects($this->any())
            ->method('render')
            ->will($this->returnCallback(function () use (&$template, &$data) {
                $args = func_get_args();
                $template = $args[1];
                $data = $args[2];

                return $args[0];
            }));

        $action         = $this->action;
        $actionResponse = $action($this->request, $this->response);
        $this->assertSame($template, 'user/login.twig');
        $this->assertContains($this->guardStorage, $data);
    }

    public function testInvokePassCredentialsToAuthAdapter()
    {
        $identity   = null;
        $credential = null;

        $adapter = $this->getMock('\\Zend\\Authentication\\Adapter\\ValidatableAdapterInterface');

        $adapter->expects($this->any())
            ->method('setIdentity')
            ->will($this->returnCallback(function () use (&$identity) {
                $args = func_get_args();
                $identity = $args[0];
            }));

        $adapter->expects($this->any())
            ->method('setCredential')
            ->will($this->returnCallback(function () use (&$credential) {
                $args = func_get_args();
                $credential = $args[0];
            }));

        $this->authService->expects($this->any())
            ->method('authenticate')
            ->with($this->isInstanceOf('\\Zend\\Authentication\\Adapter\\AdapterInterface'))
            ->will($this->returnValue(
                new Result(Result::FAILURE, null, ['Failed to login'])
            ));

        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(true));
        $this->request->expects($this->any())
            ->method('getParam')
            ->will($this->returnCallback(function () {
                $args = func_get_args();
                if ($args[0] === 'identity') {
                    return 'theIdentity';
                }
                if ($args[0] === 'credential') {
                    return 'theCredential';
                }
            }));

        $action            = new Login(
            $this->view,
            $this->authService,
            $adapter,
            $this->flash,
            $this->guard,
            $this->successUrl
        );

        $actionResponse = $action($this->request, $this->response);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $actionResponse);
        $this->assertNotNull($identity);
        $this->assertNotNull($credential);
        $this->assertSame('theIdentity', $identity);
        $this->assertSame('theCredential', $credential);
    }

    public function testInvokeSetsFlasMessageOnInvalidLoginAndRedirects()
    {
        $flashKey     = null;
        $flashMessage = null;
        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(true));
        $this->request->expects($this->any())
            ->method('getUri')
            ->will($this->returnValue('/request/uri'));

        $this->authService->expects($this->any())
            ->method('authenticate')
            ->with($this->isInstanceOf('\\Zend\\Authentication\\Adapter\\AdapterInterface'))
            ->will($this->returnValue(
                new Result(Result::FAILURE, null, ['Failed to login'])
            ));

        $this->flash->expects($this->any())
            ->method('addMessage')
            ->will($this->returnCallback(function () use (&$flashKey, &$flashMessage) {
                $args = func_get_args();
                $flashKey = $args[0];
                $flashMessage = $args[1];
            }));

        $action         = $this->action;
        $actionResponse = $action($this->request, $this->response);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $actionResponse);
        $location = $actionResponse->getHeader('Location');
        $this->assertContains('/request/uri', $location);
        $this->assertSame($flashKey, 'danger');
        $this->assertSame($flashMessage, 'Failed to login');
    }

    public function testInvokeRedirectsOnSuccessLogin()
    {
        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(true));

        $this->authService->expects($this->any())
            ->method('authenticate')
            ->with($this->isInstanceOf('\\Zend\\Authentication\\Adapter\\AdapterInterface'))
            ->will($this->returnValue(
                new Result(Result::SUCCESS, 'identity', ['Success'])
            ));

        $action         = $this->action;
        $actionResponse = $action($this->request, $this->response);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $actionResponse);
        $location = $actionResponse->getHeader('Location');
        $this->assertContains($this->successUrl, $location);
    }
}
