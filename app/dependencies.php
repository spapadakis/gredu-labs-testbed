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

$container['Service\\Authentication\\DbAdapter'] = function ($c) {
    return new \GrEduLabs\Authentication\Adapter\Pdo($c->get('db'));
};

$container['Service\\Authentication\\CasAdapter'] = function ($c) {
    $settings = $c->get('settings');

    return new GrEduLabs\Authentication\Adapter\Cas($settings['phpcas']);
};


$container['Service\\Authentication\\Storage'] = function ($c) {
    return new \GrEduLabs\Authentication\Storage\PhpSession($_SESSION);
};

$container['Service\\Authentication'] = function ($c) {
    return new \Zend\Authentication\AuthenticationService(
        $c->get('Service\\Authentication\\Storage')
    );
};

// Actions

$container['GrEduLabs\\Action\\User\\Login'] = function ($c) {
    return new GrEduLabs\Action\User\Login(
        $c->get('view'),
        function ($identity, $credential) use ($c) {
            $service = $c->get('Service\\Authentication');
            $adapter = $c->get('Service\\Authentication\\DbAdapter');
            $adapter->setIdentity($identity)
                ->setCredential($credential);

            return $service->authenticate($adapter);
        }
    );
};

$container['GrEduLabs\\Action\\User\\LoginSso'] = function ($c) {
    return new GrEduLabs\Action\User\LoginSso(function () use ($c) {
        $service = $c->get('Service\\Authentication');
        $adapter = $c->get('Service\\Authentication\\CasAdapter');

        return $service->authenticate($adapter);
    });
};

$container['GrEduLabs\\Action\\User\\Logout'] = function ($c) {
    return new GrEduLabs\Action\User\Logout(
        $c->get('Service\\Authentication'),
        $c->get('router')->pathFor('index')
    );
};
