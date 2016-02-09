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
        return new GrEduLabs\Authentication\Adapter\RedBeanPHP(
            $c['events'],
            $c['authentication_identity_class'],
            $c['authentication_crypt']
        );
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

    $container['authentication_crypt'] = function ($c) {
        $service = new Zend\Crypt\Password\Bcrypt();
        if (isset($c['settings']['authentication']['bcrypt']['salt'])) {
            $service->setSalt($c->settings['authentication']['bcrypt']['salt']);
        }
        if (isset($c['settings']['authentication']['bcrypt']['cost'])) {
            $service->setCost($c->settings['authentication']['bcrypt']['cost']);
        }

        return $service;
    };

    $events('on', 'bootstrap', function () use ($container) {
        $container->extend('view', function ($view, $c) {
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
            $view->addExtension(new GrEduLabs\Authentication\Twig\Extension\Identity(
                $c['authentication_service']
            ));

            return $view;
        });
    });

    $events('on', 'authenticate.success', function ($stop, $identity) use ($container) {
        if (isset($container['logger'])) {
            $container['logger']->info(sprintf(
                'Authentication through %s for %s',
                $identity->authenticationSource,
                $identity->mail
            ));
        }
    });

    $container[GrEduLabs\Authentication\Action\User\Login::class] = function ($c) {

        return new GrEduLabs\Authentication\Action\User\Login(
            $c['view'],
            $c['authentication_service'],
            $c['flash'],
            $c['csrf'],
            $c['router']->pathFor('index')
        );
    };

    $container[GrEduLabs\Authentication\Action\User\Logout::class] = function ($c) {
        return new GrEduLabs\Authentication\Action\User\Logout(
            $c['authentication_service'],
            $c['events'],
            $c['router']->pathFor('index')
        );
    };

    $app->group('/user', function () {
        $this->map(['GET', 'POST'], '/login', GrEduLabs\Authentication\Action\User\Login::class)
            ->setName('user.login');

        $this->get('/logout', GrEduLabs\Authentication\Action\User\Logout::class)
            ->setName('user.logout');
    });

    $nav                   = $container['settings']->get('navigation');
    $nav['authentication'] = [
        'login' => [
            'label' => 'Σύνδεση',
            'route' => 'user.login',
            'icon'  => 'unlock',
        ],
        'logout' => [
            'label' => 'Αποσύνδεση',
            'route' => 'user.logout',
            'id'    => 'nav-logout',
            'icon'  => 'lock',
        ],
    ];
    $container['settings']->set('navigation', $nav);
};
