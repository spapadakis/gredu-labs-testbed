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

    $initCas = function () use ($container) {
        $settings = $container['settings']['phpcas'];
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
        phpCAS::setDebug('data/log/phpCAS.log');
    };

    $events('on', 'bootstrap', function () use ($container) {
        $container['router']->getNamedRoute('user.login')->add(function ($req, $res, $next) {
            $method = $req->getMethod();
            $query = $req->getQueryParams();
            if ($method === 'GET' && isset($query['ticket'])) {
                $req = $req->withMethod('POST');
            }

            return $next($req, $res);
        });
    }, 10);

    $events('on', 'authenticate', function (callable $stop) use (&$initCas, $container) {

        $initCas();

        if (!phpCAS::forceAuthentication()) {
            return false;
        }

        $attributes = phpCAS::getAttributes();
        $identity = phpCAS::getUser();
        $filterAttribute = function ($attribute) use ($attributes) {
            if (!isset($attributes[$attribute])) {
                return;
            }

            if (is_array($attributes[$attribute])) {
                return $attributes[$attribute];
            }

            return $attributes[$attribute];
        };

        $stop();

        $identityClass = $container['authentication_identity_class'];

        return new $identityClass(
            $identity,
            $filterAttribute('mail'),
            $filterAttribute('cn'),
            $filterAttribute('ou'),
            'CAS'
        );
    }, -10);

    $events('on', 'logout', function (callable $stop, GrEduLabs\Authentication\Identity $identity, $redirect = null) use (&$initCas) {

        if ($identity->authenticationSource === 'CAS') {
            $initCas();
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
