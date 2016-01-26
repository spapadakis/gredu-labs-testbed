<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Authorization;

use GrEduLabs\Authorization\Acl;
use GrEduLabs\Authorization\GuardMiddleware;
use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Route;

/**
 *
 */
class GuardMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->acl = new Acl([
            'default_role' => 'guest',
            'roles'        => [
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],
            'guards' => [
                'callables' => [
                    ['CallableFunction', ['user']],
                ],
                'routes' => [
                    ['/foo', ['user'],  ['get']],
                    ['/bar', ['guest'], ['get']],
                ],
            ],
        ]);
    }

    public function testGuestSuccess()
    {
        $request = $this->requestFactory('bar');

        // Response
        $response = new Response();

        $guard = new GuardMiddleware($this->acl, 'guest');

        $next = function ($req, $res) {
            return $res;
        };
        $newResponse = $guard($request, $response, $next);
        echo $newResponse->getBody();
        $this->assertEquals(200, $newResponse->getStatusCode());
    }

    public function testGuestNotAllowedByRoute()
    {
        $request = $this->requestFactory('foo');

        // Response
        $response = new Response();

        $guard = new GuardMiddleware($this->acl, 'guest');

        $next = function ($req, $res) {
            return $res;
        };
        $newResponse = $guard($request, $response, $next);
        $this->assertEquals(403, $newResponse->getStatusCode());
    }

    public function testGuestNotAllowedByCallable()
    {
        $request = $this->requestFactory('foo');

        // Response
        $response = new Response();

        $guard = new GuardMiddleware($this->acl, 'guest');

        $next = function ($req, $res) {
            return $res;
        };
        $newResponse = $guard($request, $response, $next);
        $this->assertEquals(403, $newResponse->getStatusCode());
    }

    public function testGuestNotAllowedByDefaultCallable()
    {
        $request = $this->requestFactory('foo');

        // Response
        $response = new Response();

        $guard = new GuardMiddleware($this->acl, 'guest');

        $next = function ($req, $res) {
            return $res;
        };
        $newResponse = $guard($request, $response, $next);
        $this->assertEquals(403, $newResponse->getStatusCode());
    }

    private function requestFactory($endpoint)
    {
        // Request
        $uri          = Uri::createFromString('https://example.com:443/' . $endpoint);
        $headers      = new Headers();
        $cookies      = [];
        $serverParams = [];
        $body         = new Body(fopen('php://temp', 'r+'));
        $request      = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        $request      = $request->withAttribute('route', new Route(['get'], '/' . $endpoint, 'CallableFunction'));

        return $request;
    }
}
