<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Admin\Middleware;

use Slim\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EnableAdminLogin {

    private $_c;

    public function __construct(Container $c) {
        $this->_c = $c;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $next) {
        $route = $req->getAttribute('route');
        $routeName = $route->getName();
//        die(var_export($routeName, true));
        $groups = $route->getGroups();
        $methods = $route->getMethods();
        $arguments = $route->getArguments();
        if (isset($this->_c['logger'])) {
            $this->_c['logger']->info("Arguments: " . var_export($arguments, true));
        }

        if ($routeName === 'admin') {
            $_SESSION['enableAltLogin'] = null;
//            if (!isset($_SESSION['enableAltLogin'])) {
//                $res = $res->withRedirect($this->formUrl);
//                return $res;
//            }
//            $appForm = $_SESSION['applicationForm']['appForm'];
//            $_SESSION['applicationForm']['appForm'] = null;
//            unset($_SESSION['applicationForm']['appForm']);
//            $container = $this->_c;
//            $container['router']->getNamedRoute('user.login')->add(function ($req, $res, $next) use ($container) {
//                $container['view']['enable_database_login'] = true;
//            });
//            $container['router']->getNamedRoute('user.login')->add(function ($req, $res, $next) use ($container) {
//                $container['view']['enable_database_login'] = true;
//
//                return $next($req, $res);
//            });

            if (isset($this->_c['logger'])) {
                $this->_c['logger']->info(var_export('Hello!', true));
            }
        }

        if (isset($_SESSION['enableAltLogin'])) {
            
        }
        $container = $this->_c;
        $container['router']->getNamedRoute('user.login')->add(function ($req, $res, $next) use ($container) {
            $container['view']['enable_database_login'] = true;
        });

        return $next($req, $res);
    }

}
