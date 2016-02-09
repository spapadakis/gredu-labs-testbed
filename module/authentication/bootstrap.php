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

    $container['autoloader']->addPsr4('GrEduLabs\\Authentication\\', __DIR__ . '/src');

    $container['authentication_storage'] = function ($c) {
        return new GrEduLabs\Authentication\Storage\PhpSession();
    };

    $container['authentication_adapter'] = function ($c) {
        return new GrEduLabs\Authentication\Adapter\EventsAdapter($c['events']);
    };

    $container['authentication_service'] = function ($c) {
        return new Zend\Authentication\AuthenticationService(
            $c['authentication_storage'],
            $c['authentication_adapter']
        );
    };

    $container['authentication_identity_class'] = function ($c) {
        return GrEduLabs\Authentication\Identity::class;
    };

    $events('on', 'bootstrap', function () use ($container) {
        $container->extend('view', function ($view, $c) {
            $view->addExtension(new GrEduLabs\Authentication\Twig\Extension\Identity(
                $c['authentication_service']
            ));

            return $view;
        });
    });

    $container['GrEduLabs\\Authentication\\Action\\User\\Login'] = function ($c) {

        return new GrEduLabs\Authentication\Action\User\Login(
            $c['view'],
            $c['authentication_service'],
            $c['flash'],
            $c['csrf'],
            $c['router']->pathFor('index')
        );
    };

    $container['GrEduLabs\\Authentication\\Action\\User\\Logout'] = function ($c) {
        return new GrEduLabs\Authentication\Action\User\Logout(
            $c['authentication_service'],
            $c['events'],
            $c['router']->pathFor('index')
        );
    };

    $app->group('/user', function () {
        $this->map(['GET', 'POST'], '/login', 'GrEduLabs\\Authentication\\Action\\User\\Login')
            ->setName('user.login');

        $this->get('/logout', 'GrEduLabs\\Authentication\\Action\\User\\Logout')
            ->setName('user.logout');
    });
};
