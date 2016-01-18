<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Action\User;

use GrEduLabs\Action\User\LoginSso;
use Slim\Http\Response;
use Zend\Authentication\Result;

class LoginSsoTest extends \PHPUnit_Framework_TestCase
{
    private $action;

    private $authService;

    private $successUrl = '/some/success/url';

    private $failureUrl = '/some/failure/url';

    protected function setUp()
    {
        $this->authService = $this->getMock('\\Zend\\Authentication\\AuthenticationServiceInterface');
        $flash             = $this->getMock('\\Slim\\Flash\\Messages');
        $this->action      = new LoginSso(
            $this->authService,
            $flash,
            $this->successUrl,
            $this->failureUrl
        );
    }

    public function testConstructorSetAuthServiceProperty()
    {
        $this->assertAttributeSame($this->authService, 'authService', $this->action);
    }

    public function testInvokeRedirectsToFailureUrl()
    {
        $this->authService->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue(
                new Result(Result::FAILURE, null, ['Failed to login'])
            ));
        $request        = $this->getMock('\\Psr\\Http\\Message\\ServerRequestInterface');
        $response       = new Response();
        $action         = $this->action;
        $actionResponse = $action($request, $response);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $actionResponse);
        $this->assertTrue($actionResponse->isRedirection());
        $location = $actionResponse->getHeader('Location');
        $this->assertContains($this->failureUrl, $location);
    }

    public function testInvokeRedirectsToSuccessUrl()
    {
        $this->authService->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue(
                new Result(Result::SUCCESS, 'identity', ['Success'])
            ));
        $request        = $this->getMock('\\Psr\\Http\\Message\\ServerRequestInterface');
        $response       = new Response();
        $action         = $this->action;
        $actionResponse = $action($request, $response);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $actionResponse);
        $this->assertTrue($actionResponse->isRedirection());
        $location = $actionResponse->getHeader('Location');
        $this->assertContains($this->successUrl, $location);
    }
}
