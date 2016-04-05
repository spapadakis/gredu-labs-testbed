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
    $events    = $container['events'];

    $events('on', 'app.bootstrap', function (App $app, Container $c) {

        $app->get('/open-data', function (Request $req, Response $res) use ($c) {
            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            return $view->render($res, 'open_data/index.twig');
        })->setName('open_data');
    });
};
