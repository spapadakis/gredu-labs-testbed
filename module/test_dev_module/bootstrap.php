<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

return function (Slim\App $app) {
    // module initialization code

    $container = $app->getContainer();
    $events = $container->get('events');

    // $events('on', 'app.autoload', function ($autoloader) {
    //     // register autoloading for module classes
    //     $autoloader->addPsr4('Module\\', __DIR__ . '/src');
    // });
    // $events('on', 'app.services', function ($stop, $c) {
    //     $c[Module\SomeAction::class] = function ($c) {
    //         return new Module\SomeAction(
    //             // dependencies
    //         );
    //     };
    //     $c[Module\Middleware::class] = function ($c) {
    //         return new Module\Middleware(
    //             //dependencies
    //         );
    //     };
    // });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $router = $c['router'];
        $c->get('logger')->debug('testdevmodule app.bootstrap event');

        // add middleware
        // $app->add(Module\Middleware::class);
        // register route
        // $app->get('/some-path', Module\SomeAction::class)
        //     ->setName('some-route');
        // $app->get('/userinfo', function ($request, $response, $args) {
        // echo "Hello, " . $args['name'];
        $app->get('/testdevmodule', function (Request $req, Response $res) use ($c) {
            $c->get('logger')->debug('testdevmodule /testdevmodule route');

            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            $settings = $c->get('settings');

            return $view->render($res, 'testdevmodule.twig', [
                        'settings' => $settings
            ]);
        })->setName('test-dev');

        // requires named index route
        $router->getNamedRoute('index')->add(function (Request $req, Response $res, callable $next) use ($c) {
            $c->get('logger')->debug('testdevmodule /index route addon');
            try {
                $view = $c->get('view');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/../application/templates', 'application');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

                $view['testdevmodule_msg'] = 'Hello!';
                $c->get('logger')->debug('try try');
            } catch (\Exception $ex) {
                $c->get('logger')->error(sprintf('Exception: %s', $ex->getMessage()), ['file' => __FILE__, 'line' => __LINE__]);
            }

            return $next($req, $res);
        });

        $app->get('/testdevmodule/hello[/{name}]', function (Request $req, Response $res) use ($c) {
            $c->get('logger')->debug('testdevmodule /testdevmodule/hello route');

            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            $name = $req->getAttribute('name');
            if ($name === null) {
                $name = filter_var($req->getParam('name'), FILTER_SANITIZE_STRING);
                if (filter_var(strlen($name), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                    return $res->withRedirect($req->getUri()->getPath() . "/{$name}", 307);
                } else {
                    $name = '(δεν μας το είπες το ονοματάκι σου...)';
                }
            }

            return $view->render($res, 'hello.twig', [
                        'simple_message' => 'Γεια σου ' . $name . '!'
            ]);
        })->setName('test-dev-hello');
    });
};
