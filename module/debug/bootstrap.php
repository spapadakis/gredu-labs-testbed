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

    $events('on', 'app.services', function ($container) {
        $container['settings']->set('displayErrorDetails', true);
    });

    $events('on', 'app.services', function ($container) {
        $container->extend('view', function ($view) {
            $view->addExtension(new Twig_Extension_Debug());
            $view->getEnvironment()->enableDebug();

            return $view;
        });

        $container->extend('logger', function ($logger, $c) {
            $settings = $c['settings'];

            $logger->pushHandler(new Monolog\Handler\StreamHandler(
                $settings['logger']['debug_path'],
                Monolog\Logger::DEBUG
            ));

            return $logger;
        });
    }, -10);

//    $events('on', 'app.bootstrap', function ($app, $container) {
//        $container->get('router')->getNamedRoute('application_form')->add(function ($req, $res, $next) {
//            $school_id = $req->getAttribute('school')->id;
//            $appForm = RedBeanPHP\R::findOne('applicationform', 'school_id = ?', [$school_id]);
//            if ($appForm) {
//                RedBeanPHP\R::trash($appForm);
//            }
//
//            return $next($req, $res);
//        });
//    }, -10);
};
