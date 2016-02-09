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

    $container['autoloader']->addPsr4('GrEduLabs\\Authorization\\', __DIR__ . '/src');

    $container['settings']->set('determineRouteBeforeAppMiddleware', true);

    $container[GrEduLabs\Authorization\Acl::class] = function ($c) {
        $settings = $c['settings'];

        return new GrEduLabs\Authorization\Acl($settings['acl'], $c);
    };

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

    $container[GrEduLabs\Authorization\RoleListener::class] = function ($c) {
        return new GrEduLabs\Authorization\RoleListener($c['authentication_storage']);
    };

    $events = $container['events'];

    $events('on', 'authenticate.success', function ($stop, $identity) use ($container) {
        $listener = $container[GrEduLabs\Authorization\RoleListener::class];
        $listener($stop, $identity);
    });

    $events('on', 'bootstrap', function () use ($app, $container) {

        $container->extend('authentication_identity_class', function ($c) {
            return GrEduLabs\Authorization\Identity::class;
        });

        $container->extend(GrEduLabs\Application\Twig\Extension\Navigation::class, function ($navigation, $c) {
            return $navigation
                ->setAcl($c[GrEduLabs\Authorization\Acl::class])
                ->setCurrentRole(call_user_func($c['current_role']));
        });

        $app->add(GrEduLabs\Authorization\RouteGuard::class);
    });

};
