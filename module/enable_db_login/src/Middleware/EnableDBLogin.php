<?php

/**
 * EnableDBLogin enables the database login form for specific routes or urls.
 * To use set the enable_db_login.{global,local}.php 
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\EnableDBLogin\Middleware;

use Slim\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EnableDBLogin {

    private $_c;

    public function __construct(Container $c) {
        $this->_c = $c;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $next) {
        $route = $req->getAttribute('route');
        if ($route) {
            $routeName = $route->getName();
            $routePattern = $route->getPattern();

            $dblogin_settings = $this->_c->get('settings')->get('enabledblogin');
            if ($dblogin_settings) {
                $enableRouteNames = isset($dblogin_settings['enable_routes']) ? $dblogin_settings['enable_routes'] : [];
                $enableRoutePatterns = isset($dblogin_settings['enable_patterns']) ? $dblogin_settings['enable_patterns'] : [];
                $disableRouteNames = isset($dblogin_settings['disable_routes']) ? $dblogin_settings['disable_routes'] : [];
                $disableRoutePatterns = isset($dblogin_settings['disable_patterns']) ? $dblogin_settings['disable_patterns'] : [];

                if (in_array($routeName, $enableRouteNames) ||
                        in_array($routePattern, $enableRoutePatterns)) {
                    $_SESSION['enableDLogin'] = true;
                    $this->_c['logger']->info("SET enableDLogin via route=[" . var_export($routeName, true) . '], path=[' . var_export($routePattern, true) . ']');
                }

                if (in_array($routeName, $disableRouteNames) ||
                        in_array($routePattern, $disableRoutePatterns)) {
                    unset($_SESSION['enableDLogin']);
                    $this->_c['logger']->info("UNSET enableDLogin via route=[" . var_export($routeName, true) . '], path=[' . var_export($routePattern, true) . ']');
                }
            }
        }
        return $next($req, $res);
    }

}
