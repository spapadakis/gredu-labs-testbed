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
        $autoloader->addPsr4('GrEduLabs\\Application\\', __DIR__ . '/src');
    });

    $events('on', 'app.services', function ($stop, Slim\Container $container) {
        session_name('GrEduLabs');
        session_start();

        // setup RedbeanPHP
        define('REDBEAN_MODEL_PREFIX', '');
        RedBeanPHP\R::setup(
            $container['settings']['db']['dsn'],
            $container['settings']['db']['user'],
            $container['settings']['db']['pass'],
            isset($container['settings']['db']['freeze']) ? $container['settings']['db']['freeze'] : true
        );

        // override default router
        $container['router'] = $container->extend('router', function () {
            return new GrEduLabs\Application\Router();
        });

        $container['view'] = function ($c) {
            $settings = $c['settings'];
            $view     = new Slim\Views\Twig(
                $settings['view']['template_path'],
                $settings['view']['twig']
            );
            $view->addExtension(new Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));

            $view->addExtension($c[GrEduLabs\Application\Twig\Extension\Navigation::class]);
            $view->addExtension(new Knlv\Slim\Views\TwigMessages(
                $c['flash']
            ));

            return $view;
        };

        $container[GrEduLabs\Application\Twig\Extension\Navigation::class] = function ($c) {
            return new GrEduLabs\Application\Twig\Extension\Navigation(
                $c['settings']['navigation'],
                $c['router'],
                $c['request']
            );
        };

        $container['flash'] = function ($c) {
            return new \Slim\Flash\Messages();
        };

        $container['logger'] = function ($c) {
            $settings = $c['settings'];
            $logger   = new Monolog\Logger($settings['logger']['name']);
            $logger->pushProcessor(new Monolog\Processor\UidProcessor());
            $logger->pushHandler(new Monolog\Handler\RotatingFileHandler(
                $settings['logger']['path'],
                $settings['logger']['max_files'],
                Monolog\Logger::INFO
            ));

            return $logger;
        };

        $container['csrf'] = function ($c) {
            return new \Slim\Csrf\Guard();
        };

        $container[GrEduLabs\Application\Middleware\AddCsrfToView::class] = function ($c) {
            return new GrEduLabs\Application\Middleware\AddCsrfToView(
                $c->get('view'),
                $c->get('csrf')
            );
        };

        $container[GrEduLabs\Application\Action\Index::class] = function ($c) {
            return new GrEduLabs\Application\Action\Index($c['view']);
        };
        $container[GrEduLabs\Application\Action\About::class] = function ($c) {
            return new GrEduLabs\Application\Action\About($c['view']);
        };
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $app->get('/', GrEduLabs\Application\Action\Index::class)->setName('index');
        $app->get('/about', GrEduLabs\Application\Action\About::class)->setName('about');
    });
};
