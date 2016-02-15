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
        $autoloader->addPsr4('GrEduLabs\\Authorization\\', __DIR__ . '/src');
    });

    $events('on', 'app.services', function ($stop, $container) {
        $container['settings']->set('determineRouteBeforeAppMiddleware', true);

        $container[GrEduLabs\Authorization\Acl::class] = function ($c) {
            $settings = $c['settings'];

            return new GrEduLabs\Authorization\Acl($settings['acl'], $c);
        };

        $container['acl'] = $container->protect(function () use ($container) {
            return $container[GrEduLabs\Authorization\Acl::class];
        });

        $container['current_role'] = $container->protect(function () use ($container) {
            $settings    = $container['settings'];
            $defaultRole = $settings['acl']['default_role'];
            $identity    = $container['authentication_service']->getIdentity();
            if ($identity && $identity instanceof GrEduLabs\Authorization\RoleAwareInterface &&
                ($role = $identity->getRole())) {
                return $role;
            }

            return $defaultRole;
        });

        $container[GrEduLabs\Authorization\RouteGuard::class] = function ($c) {
            $role = call_user_func($c['current_role']);

            return new GrEduLabs\Authorization\RouteGuard($c[GrEduLabs\Authorization\Acl::class], $role);
        };

        $container[GrEduLabs\Authorization\Middleware\RoleProvider::class] = function ($c) {
            return new GrEduLabs\Authorization\Middleware\RoleProvider(
                $c['authentication_service'],
                $c[GrEduLabs\Authorization\Acl::class]
            );
        };

        $container[GrEduLabs\Authorization\Listener\RoleProvider::class] = function ($c) {
            return new GrEduLabs\Authorization\Listener\RoleProvider(
                $c['authentication_storage'],
                $c[GrEduLabs\Authorization\Acl::class]
            );
        };
    });

    $events('on', 'app.services', function ($stop, $container) {
        $container->extend('identity_class_resolver', function () {
            return function () {
                return 'GrEduLabs\\Authorization\\Identity';
            };
        });

        $container->extend(GrEduLabs\Application\Twig\Extension\Navigation::class, function ($navigation, $c) {
            return $navigation
                ->setAcl($c[GrEduLabs\Authorization\Acl::class])
                ->setCurrentRole(call_user_func($c['current_role']));
        });
    }, -10);

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        foreach ($container['router']->getRoutes() as $route) {
            if ('user.login' === $route->getName()) {
                $route->add(GrEduLabs\Authorization\Middleware\RoleProvider::class);
                break;
            }
        }

        $app->add(GrEduLabs\Authorization\RouteGuard::class);
    });

};
