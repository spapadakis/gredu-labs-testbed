<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Acl;

use GrEduLabs\Authorization\Acl;
use Zend\Permissions\Acl\Assertion\CallbackAssertion;

/**
 *
 */
class AclTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $containerMock = $this->getMock('Interop\Container\ContainerInterface');
        $containerMock->expects($this->any())
            ->method('has')
            ->will($this->returnValueMap([
                ['assertionCallbackTrue', true],
                ['assertionCallbackFalse', true],
            ]));
        $containerMock->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                ['assertionCallbackTrue', function () {
                    return true;
                }],
                ['assertionCallbackFalse', new CallbackAssertion(function () {
                    return false;
                })],
            ]));

        $this->acl = new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'resources' => [
                'banana' => null,
                'orange' => null,
            ],
            'guards' => [
                'resources' => [
                    ['banana', ['user'], ['peel']],
                    ['banana', ['admin']],
                    ['orange', ['guest'], ['peel']],
                    ['orange', ['user'], ['peel'], 'assertionCallbackTrue'],
                    ['orange', ['user'], ['eat'], 'assertionCallbackFalse'],
                    ['orange', ['admin'], ['eat'], function () {
                        return true;
                    }],
                ],
                'callables' => [
                    ['CallableFunction', ['user']],
                    ['OtherCallableFunction', ['user'], new CallbackAssertion(function () {
                        return false;
                    })],
                ],
                'routes' => [
                    ['/foo', ['user'],  ['get']],
                    ['/bar', ['guest'], ['get']],
                ],
            ],
        ], $containerMock);
    }

    public function testExceptionFromUnexpectedGuardType()
    {
        $this->setExpectedException('Exception', 'Error Processing Request');
        new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'guards' => [
                'foo' => [
                    ['CallableFunction', ['user']],
                ],
            ],
        ]);
    }

    public function testExceptionFromCallablesArgCount()
    {
        $this->setExpectedException('Exception', 'Error Processing Request');
        new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'guards' => [
                'callables' => [
                    ['CallableFunction'],
                ],
            ],
        ]);
    }

    public function testExceptionFromRoutesArgCount()
    {
        $this->setExpectedException('Exception', 'Error Processing Request');
        new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'guards' => [
                'routes' => [
                    ['/foo', ['user']],
                ],
            ],
        ]);
    }

    public function testExceptionInvalidAssertion()
    {
        $this->setExpectedException('Exception', 'Error Processing Request');
        new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'guards' => [
                'routes' => [
                    ['/foo', ['user'], ['get'], 'SomeString'],
                ],
            ],
        ]);
    }

    public function testResourcePermissionFail()
    {
        $this->assertFalse($this->acl->isAllowed('guest', 'banana'));
    }


    public function testRoutePermissionFail()
    {
        $this->assertFalse($this->acl->isAllowed('guest', 'route/foo'));
    }


    public function testCallablePermissionFail()
    {
        $this->assertFalse($this->acl->isAllowed('guest', 'callable/CallableFunction'));
    }

    public function testResourcePermissionSuccess()
    {
        $this->assertTrue($this->acl->isAllowed('admin', 'banana'));
    }

    public function testResourcePrivilegePermissionSuccess()
    {
        $this->assertTrue($this->acl->isAllowed('user', 'banana', 'peel'));
    }

    public function testRoutePermissionSuccess()
    {
        $this->assertTrue($this->acl->isAllowed('user', 'route/foo', 'get'));
    }

    public function testCallablePermissionSuccess()
    {
        $this->assertTrue($this->acl->isAllowed('user', 'callable/CallableFunction'));
    }

    public function testResourcePermissionSuccessAssertionFromContainer()
    {
        $this->assertTrue($this->acl->isAllowed('user', 'orange', 'peel'));
    }

    public function testResourcePermissionFailAssertionFromContainer()
    {
        $this->assertFalse($this->acl->isAllowed('user', 'orange', 'eat'));
    }

    public function testResourcePermissionFailAssertionCallable()
    {
        $this->assertTrue($this->acl->isAllowed('admin', 'orange', 'eat'));
    }

    public function testCallablePermissionFailAssertion()
    {
        $this->assertFalse($this->acl->isAllowed('user', 'callable/OtherCallableFunction'));
    }
}
