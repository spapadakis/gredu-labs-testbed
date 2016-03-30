<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (\Slim\App $app) {

    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.bootstrap', function ($app, $c) {
        $router = $c['router'];
        $route = $router->getNamedRoute('index');
        $route->add(function (Slim\Http\Request $req, Slim\Http\Response $res, callable $next) use ($c) {
            $view = $c->get('view');
            try {
                $view['total_schools'] = RedBeanPHP\R::count('school');
                $view['total_app_forms'] = (int) RedBeanPHP\R::getCell(
                    'SELECT COUNT(*) FROM (SELECT id FROM applicationform GROUP BY school_id) AS cnt'
                );
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/../application/templates', 'application');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
            } catch (\Exception $ex) {
                $c->get('logger')->error(sprintf('Exception: %s', $ex->getMessage()), ['file' => __FILE__, 'line' => __LINE__]);
            }

            return $next($req, $res);
        });

    }, -10);
};
