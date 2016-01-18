<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */


$container = $app->getContainer();

// Twig

$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view     = new \Slim\Views\Twig(
        $settings['view']['template_path'],
        $settings['view']['twig']
    );
    $view->addExtension(new Slim\Views\TwigExtension(
        $c->get('router'),
        $c->get('request')->getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new GrEduLabs\Twig\Extension\Flash(
        $c->get('flash'),
        'flash.twig'
    ));

    return $view;
};

// Flash messages

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// Monolog

$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger   = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler(
        $settings['logger']['path'],
        \Monolog\Logger::DEBUG
    ));

    return $logger;
};

$container['events'] = function ($c) {
    return new \Zend\EventManager\EventManager(
        new \Zend\EventManager\SharedEventManager(),
        ['events']
    );
};

// Database

$container['db'] = function ($c) {
    $settings = $c->get('settings');
    try {
        $pdo = new \PDO(
            $settings['db']['dsn'],
            $settings['db']['user'],
            $settings['db']['pass'],
            $settings['db']['options']
        );

        return $pdo;
    } catch (\PDOException $e) {
        $c->get('logger')->error($e->getMessage());

        return;
    }
};

// Authentication service

$container['authentication_db_adapter'] = function ($c) {
    return new \GrEduLabs\Authentication\Adapter\Pdo($c->get('db'));
};

$container['authentication_cas_adapter'] = function ($c) {
    $settings = $c->get('settings');

    return new GrEduLabs\Authentication\Adapter\Cas($settings['phpcas']);
};


$container['authentication_storage'] = function ($c) {
    return new \GrEduLabs\Authentication\Storage\PhpSession();
};

$container['authentication_service'] = function ($c) {
    return new \Zend\Authentication\AuthenticationService(
        $c->get('authentication_storage')
    );
};

$container['maybe_identity'] = function ($c) {
    return function ($call) use ($c) {

        $authService = $c->get('authentication_service');
        if (!$authService->hasIdentity()) {
            return;
        }

        $identity = $authService->getIdentity();
        if (method_exists($identity, $call)) {
            $args = array_slice(func_get_args(), 1);

            return call_user_func_array([$identity, $call], $args);
        }

        if (property_exists($identity, $call)) {
            return $identity->{$call};
        }

        return;

    };
};

// Actions

$container['GrEduLabs\\Action\\User\\Login'] = function ($c) {
    $service = $service = $c->get('authentication_service');
    $adapter = $c->get('authentication_db_adapter');
    $service->setAdapter($adapter);

    return new GrEduLabs\Action\User\Login($c->get('view'), $service, $adapter, $c->get('flash'));
};

$container['GrEduLabs\\Action\\User\\LoginSso'] = function ($c) {
    $service = $c->get('authentication_service');
    $adapter = $c->get('authentication_cas_adapter');
    $service->setAdapter($adapter);

    return new GrEduLabs\Action\User\LoginSso(
        $service,
        $c->get('flash'),
        $c->get('router')->pathFor('index'),
        $c->get('router')->pathFor('user.login')
    );
};

$container['GrEduLabs\\Action\\User\\Logout'] = function ($c) {
    return new GrEduLabs\Action\User\Logout(
        $c->get('authentication_service'),
        $c->get('router')->pathFor('index')
    );
};
