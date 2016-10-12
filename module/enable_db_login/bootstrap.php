<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

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
        $autoloader->addPsr4('GrEduLabs\\EnableDBLogin\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function (Container $container) {
        $container[GrEduLabs\EnableDBLogin\Middleware\EnableDBLogin::class] = function ($c) {
            return new GrEduLabs\EnableDBLogin\Middleware\EnableDBLogin($c);
        };

        $container[GrEduLabs\EnableDBLogin\Action\Index::class] = function ($c) {
            return new GrEduLabs\EnableDBLogin\Action\Index($c);
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $app->add(GrEduLabs\EnableDBLogin\Middleware\EnableDBLogin::class);
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        if (isset($_SESSION['enableDLogin'])) {
            $c['router']->getNamedRoute('user.login')->add(function (Request $req, Response $res, callable $next) use ($c) {
                $c['view']['enable_database_login'] = true;
                return $next($req, $res);
            });
        }
    }, -98); // must be in order to set last

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $app->get('/enabledblogin', GrEduLabs\EnableDBLogin\Action\Index::class)
                ->setName('enabledblogin');
        $app->get('/disabledblogin', GrEduLabs\EnableDBLogin\Action\Index::class)
                ->setName('disabledblogin');
    });
};
