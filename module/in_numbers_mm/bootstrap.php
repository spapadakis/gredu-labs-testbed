<?php

use Slim\App;
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

    $events('on', 'app.services', function ($c) {
        $c['in_numbers_get_totals_from_mm'] = function ($c) {

            $settings = $c['settings'];
            $httpClient = new GuzzleHttp\Client([
                'base_uri' => $settings['sch_mm']['api_url'],
                'auth'     => [
                    $settings['sch_mm']['api_user'],
                    $settings['sch_mm']['api_pass'],
                ],
            ]);

            return function ($type) use ($httpClient) {

                $config       = $httpClient->getConfig();
                $baseUri      = $config['base_uri'];
                $auth         = $config['auth'];
                $url          = $baseUri->withQueryValue($baseUri, 'unit_type', $type);
                $url          = $url->withQueryValue($url, 'pagesize', 1);
                $url          = $url->withQueryValue($url, 'state', 1);
                $response     = $httpClient->request('GET', $url, ['auth' => $auth]);
                $responseData = json_decode($response->getBody()->getContents(), true);

                return isset($responseData['total']) ? $responseData['total'] : null;
            };
        };

        $c['in_numbers_get_totals_from_mm'] = $c->extend('in_numbers_get_totals_from_mm', function ($fn, $c) {
            $settings = $c['settings'];
            $cacheFile = isset($settings['in_numbers']['cache']) ? $settings['in_numbers']['cache'] : false;
            $cacheLifetime = isset($settings['in_numbers']['cache_lifetime']) ? $settings['in_numbers']['cache_lifetime'] : false;
            if (!$cacheFile) {
                return $fn;
            }

            return function ($type) use (&$fn, $cacheFile, $cacheLifetime) {

                $cache = [];
                if (file_exists($cacheFile) && !(time() - filemtime($cacheFile) > $cacheLifetime * 60)) {
                    $cache = include $cacheFile;
                    if (isset($cache[$type])) {
                        return $cache[$type];
                    }
                }

                $result = $fn($type);
                $cache[$type] = $result;

                file_put_contents($cacheFile, '<?php return ' . var_export($cache, true) . ';');

                return $result;
            };
        });

        $c['in_numbers_by_school_type'] = $c->extend('in_numbers_by_school_type', function ($fn, $c) {
            $fromMMFunction = $c['in_numbers_get_totals_from_mm'];

            return function () use (&$fn, &$fromMMFunction) {
                $result = $fn();
                try {
                    $result = array_map(function ($type) use (&$fromMMFunction) {
                        $total = $fromMMFunction($type['type_id']);
                        $type['schools_total'] = $total;

                        return $type;
                    }, $result);
                } catch (\Exception $ex) {
                    // eat it
                }

                return $result;
            };
        });

    });

    $events('on', 'app.bootstrap', function ($app, $c) {
        $router = $c['router'];
        $view = $c['view'];
        $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $router->getNamedRoute('in_numbers')->add(function (Request $req, Response $res, $next) use ($c) {
            $inNumbersFunction = $c['in_numbers_by_school_type'];
            $view = $c['view'];
            $schoolTypes = $inNumbersFunction();
            $view['schools_total_global'] = array_reduce($schoolTypes, function ($total, $type) {
                if (isset($type['schools_total'])) {
                    if (null === $total) {
                        $total = 0;
                    }
                    $total += $type['schools_total'];
                }

                return $total;
            }, null);

            if (null === $view['schools_total_global']) {
                unset($view['schools_total_global']);
            }


            return $next($req, $res);
        });
    }, -10);
};
