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

    $events = $container['events'];

    $events('on', 'app.autoload', function ($stop, $autoloader) {
        $autoloader->addPsr4('SchSSO\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($stop, $container) {
        $container['init_cas'] = $container->protect(function () use ($container) {
            $settings = $container['settings']['sso']['phpcas'];
            phpCAS::client(
                $settings['serverVersion'],
                $settings['serverHostname'],
                $settings['serverPort'],
                $settings['serverUri'],
                $settings['changeSessionId']
            );
            if (($casServerCaCert = $settings['casServerCaCert'])) {
                if ($settings['casServerCnValidate']) {
                    phpCAS::setCasServerCACert($casServerCaCert, true);
                } else {
                    phpCAS::setCasServerCACert($casServerCaCert, false);
                }
            }

            if ($settings['noCasServerValidation']) {
                phpCAS::setNoCasServerValidation();
            }
            phpCAS::handleLogoutRequests();
        });

        $container['is_allowed'] = $container->protect(function ($attributes) use ($container) {

            $allowed = isset($container['settings']['sso']['allowed'])
                ? $container['settings']['sso']['allowed'] : [];

            foreach ($allowed as $index => $ruleset) {
                $isAllowed[$index] = true;
                foreach ($ruleset as $attribute => $rule) {
                    if (!isset($attributes[$attribute])) {
                        $isAllowed[$index] = false;
                        break;
                    }
                    if (!is_array($attributes[$attribute])) {
                        $attributes[$attribute] = [$attributes[$attribute]];
                    }
                    foreach ($attributes[$attribute] as $value) {
                        $isAllowed[$index] &= (1 === preg_match($rule, $value));
                    }
                }
            }

            return array_reduce($isAllowed, function ($result, $value) {
                return $result || $value;
            }, false);
        });

        $container[SchSSO\Adapter\Cas::class] = function ($c) {
            return new SchSSO\Adapter\Cas(
                $c['init_cas'],
                $c['is_allowed'],
                $c['events'],
                $c['identity_class_resolver'],
                $c['router']->pathFor('user.logout.sso')
            );
        };

        $container[SchSSO\Action\Login::class] = function ($c) {
            $authService = $c['authentication_service'];
            $authService->setAdapter($c[SchSSO\Adapter\Cas::class]);

            return new SchSSO\Action\Login(
                $authService,
                $c['flash'],
                $c['router']->pathFor('index'),
                $c['router']->pathFor('user.login')
            );
        };

        $container[SchSSO\Action\Logout::class] = function ($c) {
            return new SchSSO\Action\Logout($c['init_cas']);
        };
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
        $app->get('/user/login/sso', SchSSO\Action\Login::class)
            ->setName('user.login.sso');

        $app->get('/user/logout/sso', SchSSO\Action\Logout::class)
            ->setName('user.logout.sso');
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $container['router']->getNamedRoute('user.login.sso')
            ->add(GrEduLabs\Authorization\Middleware\RoleProvider::class);
    }, -100);

    $events('on', 'logout', function (
        callable $stop,
        GrEduLabs\Authentication\Identity $identity,
        $redirect = null
    ) use (&$container) {

        if ($identity->authenticationSource === 'CAS') {
            call_user_func($container['init_cas']);
            if (!phpCAS::isAuthenticated()) {
                return;
            }

            if ($redirect) {
                phpCAS::logout(['url' => (string) $redirect]);
            }
            phpCAS::logout();
        }
    });
};
