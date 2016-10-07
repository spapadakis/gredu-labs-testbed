<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use GrEduLabs\open_data\Action;
use GrEduLabs\open_data\Service;

return function (App $app) {
    $container = $app->getContainer();
    $events = $container['events'];

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\open_data\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($container) {

        // actions

        $container[Action\Index::class] = function ($c) {
            return new Action\Index(
                    $c, $c->get(Service\ODAServiceInterface::class)
            );
        };

        // services

        $container['odaservice'] = function ($c) {
            return $c->get(Service\ODAServiceInterface::class);
        };

        $container[Service\ODAServiceInterface::class] = function ($c) {
            return new Service\ODAService();
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {

        $app->get('/open-data', function (Request $req, Response $res) use ($c) {
            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            return $view->render($res, 'open_data/index.twig');
        })->setName('open_data');

      $app->get('/open-data/api', Action\Index::class
     )->setName('open_data_api');

 
    });
};
