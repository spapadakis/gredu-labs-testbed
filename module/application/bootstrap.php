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

    $container['autoloader']->addPsr4('GrEduLabs\\Application\\', __DIR__ . '/src');

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

    $container['authenticate_redbean_listener'] = function ($c) {
        return new GrEduLabs\Application\Authentication\RedbeanListener();
    };

    $container['csrf'] = function ($c) {
        return new \Slim\Csrf\Guard();
    };

    $events = $container['events'];
    $events('on', 'bootstrap', function () use ($container) {
        session_name('GrEduLabs');
        session_start();

        // setup RedbeanPHP

        RedBeanPHP\R::setup(
            $container['settings']['db']['dsn'],
            $container['settings']['db']['user'],
            $container['settings']['db']['pass']
        );

        define('REDBEAN_MODEL_PREFIX', 'GrEduLabs\\Application\\Model\\');

    }, 10000000);

    $events('on', 'bootstrap', function () use ($container) {
        $container['router']->getNamedRoute('user.login')->add('csrf');
    });

    $events('on', 'authenticate', $container['authenticate_redbean_listener']);

    $container['GrEduLabs\\Application\\Action\\Index'] = function ($c) {
        return new GrEduLabs\Application\Action\Index($c['view']);
    };

    $container['GrEduLabs\\Application\\Action\\School\\Index'] = function ($c) {
        return new GrEduLabs\Application\Action\School\Index($c->get('view'));
    };

    $container['GrEduLabs\\Application\\Action\\School\\Staff'] = function ($c) {
        return new GrEduLabs\Application\Action\School\Staff($c->get('view'));
    };

    $container['GrEduLabs\\Application\\Action\\School\\Labs'] = function ($c) {
        return new GrEduLabs\Application\Action\School\Labs($c->get('view'));
    };

    $container['GrEduLabs\\Application\\Action\\School\\Assets'] = function ($c) {
        return new GrEduLabs\Application\Action\School\Assets($c->get('view'));
    };

    $app->get('/', 'GrEduLabs\\Application\\Action\\Index')->setName('index');
    $app->group('/school', function () {
        $this->get('', 'GrEduLabs\\Application\\Action\\School\\Index')->setName('school');
        $this->get('/staff', 'GrEduLabs\\Application\\Action\\School\\Staff')->setName('school.staff');
        $this->get('/labs', 'GrEduLabs\\Application\\Action\\School\\Labs')->setName('school.labs');
        $this->get('/assets', 'GrEduLabs\\Application\\Action\\School\\Assets')->setName('school.assets');
    });
};
