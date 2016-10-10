<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use RedBeanPHP\R;

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
return function (App $app) {
    $container = $app->getContainer();
    $events = $container['events'];

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\Admin\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function (Container $container) {
        $nav = $container['settings']->get('navigation');
        $nav['admin'] = [
            'adminhome' => [
                'label' => 'Διαχειριστής',
                'route' => 'admin',
                'icon' => 'user-secret',
            ],
        ];
//        echo "<pre>"; var_export($nav); echo "</pre>"; die();
        $container['settings']->set('navigation', $nav);


        $container[GrEduLabs\Admin\Middleware\EnableAdminLogin::class] = function ($c) {
            return new GrEduLabs\Admin\Middleware\EnableAdminLogin($c);
        };

        $container[GrEduLabs\Admin\Action\Index::class] = function ($c) {
            return new GrEduLabs\Admin\Action\Index($c['view']);
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $view = $c->get('view');
        $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->add(GrEduLabs\Admin\Middleware\EnableAdminLogin::class);

//        $app->get('/admin', function (Request $req, Response $res) use ($c) {
//            return $view->render($res, 'admin/index.twig');
//        })->setName('admin');
//        $app->get('/adminlogin', GrEduLabs\Admin\Middleware\EnableAdminLogin::class);
        $app->get('/admin', GrEduLabs\Admin\Action\Index::class)
                ->setName('admin');
    });
};
