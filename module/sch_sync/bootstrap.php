<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (Slim\App $app) {

    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.autoload', function ($stop, $autoloader) {
        $autoloader->addPsr4('SchSync\\', __DIR__ . '/src');
    });

    $events('on', 'app.services', function ($stop, $container) {
        $container[SchSync\Middleware\CreateUser::class] = function ($c) {
            return new SchSync\Middleware\CreateUser(
                $c['authentication_service'],
                $c['router']->pathFor('user.login'),
                $c['router']->pathFor('user.logout.sso'),
                $c['flash'],
                $c['logger']
            );
        };
        $container[SchSync\Middleware\CreateSchool::class] = function ($c) {
            return new SchSync\Middleware\CreateSchool(
                $c['ldap'],
                $c[SchMM\FetchUnit::class],
                $c['authentication_service'],
                $c['router']->pathFor('user.login'),
                $c['router']->pathFor('user.logout.sso'),
                $c['flash'],
                $c['logger']
            );
        };
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        foreach ($container['router']->getRoutes() as $route) {
            if ('user.login.sso' === $route->getName()) {
                $route->add(SchSync\Middleware\CreateUser::class)
                    ->add(SchSync\Middleware\CreateSchool::class);
                break;
            }
        }
    }, -10);
};
