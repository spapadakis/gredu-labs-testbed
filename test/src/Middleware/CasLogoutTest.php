<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Middleware;

use GrEduLabs\Middleware\CasLogout;
use Slim\Http\Response;

class CasLogoutTest extends \PHPUnit_Framework_TestCase
{
    protected $middleware;

    protected $adapter;

    protected $idenityStub;

    protected $logoutFlag = false;

    protected function setUp()
    {
        $this->request  = $this->getMock('\\Psr\\Http\\Message\\ServerRequestInterface');
        $this->response = new Response();

        $this->idenityStub = $this->getMockBuilder('GrEduLabs\Authentication\Identity')
            ->disableOriginalConstructor()
            ->getMock();


        $this->adapter = $this->getMockBuilder('\\GrEduLabs\\Authentication\\Adapter\\Cas')
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter->expects($this->any())
            ->method('logout')
            ->with($this->anything())
            ->will($this->returnCallback(function () {
                $this->logoutFlag = true;
            }));

        $this->logoutFlag = false;

        $this->middleware = new CasLogout($this->adapter);
    }

    public function testInvokeCallsCasLogoutIfIdentitySourceIsCAS()
    {
        $this->idenityStub->expects($this->any())
            ->method('__get')
            ->with($this->equalTo('authenticationSource'))
            ->will($this->returnValue('CAS'));
        $this->request->expects($this->any())
            ->method('getAttribute')
            ->with($this->equalTo('identity'))
            ->will($this->returnValue($this->idenityStub));
        $middleware = $this->middleware;
        $middleware($this->request, $this->response, function ($req, $res) {
            return $res;
        });
        $this->assertTrue($this->logoutFlag);
    }

    public function testInvokeNotCallsCasLogoutIfIdentitySourceIsNotCAS()
    {
        $this->idenityStub->expects($this->any())
            ->method('__get')
            ->with($this->equalTo('authenticationSource'))
            ->will($this->returnValue('SomeOtherSource'));
        $this->request->expects($this->any())
            ->method('getAttribute')
            ->with($this->equalTo('identity'))
            ->will($this->returnValue($this->idenityStub));
        $middleware = $this->middleware;
        $middleware($this->request, $this->response, function ($req, $res) {
            return $res;
        });
        $this->assertFalse($this->logoutFlag);
    }

    public function testInvokeNotCallsCasLogoutIfNoIdentityInRequest()
    {
        $this->request->expects($this->any())
            ->method('getAttribute')
            ->with($this->equalTo('identity'))
            ->will($this->returnValue(null));
        $middleware = $this->middleware;
        $middleware($this->request, $this->response, function ($req, $res) {
            return $res;
        });
        $this->assertFalse($this->logoutFlag);
    }
}
