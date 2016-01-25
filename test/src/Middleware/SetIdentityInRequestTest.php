<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Middleware;

use GrEduLabs\Middleware\SetIdentityInRequest;

class SetIdentityInRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSetIdentityInRequestIfIdentityExists()
    {
        $flag     = false;
        $identity = $this->getMockBuilder('GrEduLabs\Authentication\Identity')
            ->disableOriginalConstructor()
            ->getMock();

        $authService = $this->getMock('Zend\\Authentication\\AuthenticationServiceInterface');
        $authService->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        $authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($identity));

        $request =  $this->getMock('Psr\\Http\\Message\\ServerRequestInterface');
        $request->expects($this->any())
            ->method('withAttribute')
            ->will($this->returnCallback(function ($name, $value) use ($identity, &$flag) {
                $flag = $identity === $value;
            }));

        $response = $this->getMock('Psr\\Http\\Message\\ResponseInterface');

        $middleware = new SetIdentityInRequest($authService);
        $middleware($request, $response, function () {});
        $this->assertTrue($flag);
    }
}
