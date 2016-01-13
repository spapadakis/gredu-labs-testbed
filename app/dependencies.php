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
        new \Zend\EventManager\SharedEventManager()
    );
};

$container['Service\\Authentication\\Adapter'] = function ($c) use ($app) {
    return new \Zend\Authentication\Adapter\Callback(function () use ($c) {
        return $c->events->triggerUntil(
            function (\Zend\Authentication\Result $result) {
                return $result->isValid();
            },
            'authenticate', $app, func_get_args()
        )->last();
    });
};

$container['Service\\Authentication'] = function ($c) {

    $service = new \Zend\Authentication\AuthenticationService(
        new \GrEduLabs\Authentication\Storage\PhpSession($_SESSION),
        $c->get('Service\\Authentication\\Adapter')
    );

    return $service;
};
