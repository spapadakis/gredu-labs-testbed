<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Action\User;

use GrEduLabs\Action\User\Logout;
use Slim\Http\Response;

class LogoutTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    private $response;

    private $authService;

    private $clearIdentityFlag;

    private $redirectUrl = '/some/success/url';

    protected function setUp()
    {
        $this->request     = $this->getMock('\\Psr\\Http\\Message\\ServerRequestInterface');
        $this->response    = new Response();
        $this->authService = $this->getMock('\\Zend\\Authentication\\AuthenticationServiceInterface');
        $this->authService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        $this->authService->expects($this->any())
            ->method('clearIdentity')
            ->will($this->returnCallback(function () {
                $this->clearIdentityFlag = true;
            }));

        $this->clearIdentityFlag = false;

        $this->action = new Logout(
            $this->authService,
            $this->redirectUrl
        );
    }

    public function testConstructorSetsDependencies()
    {
        $this->assertAttributeSame($this->authService, 'authService', $this->action);
        $this->assertAttributeSame($this->redirectUrl, 'redirectUrl', $this->action);
    }

    public function testInvokeClearsIdentity()
    {
        $action         = $this->action;
        $actionResponse = $action($this->request, $this->response);
        $this->assertTrue($this->clearIdentityFlag);
    }

    public function testInvokeRedirects()
    {
        $action         = $this->action;
        $actionResponse = $action($this->request, $this->response);
        $location       = $actionResponse->getHeader('Location');
        $this->assertContains($this->redirectUrl, $location);
    }
}
