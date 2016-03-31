<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (\Slim\App $app) {

    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.services', function ($c) {

        $c['in_numbers_totals'] = function ($c) {
            return function () {
                return [
                    'schools_cnt'  => RedBeanPHP\R::count('school'),
                    'appforms_cnt' => (int) RedBeanPHP\R::getCell(
                        'SELECT COUNT(distinct school_id ) FROM applicationform'
                    ),
                ];
            };
        };

        $c['in_numbers_by_school_type'] = function ($c) {
            return function () {
                $schoolTypes  = RedBeanPHP\R::getAssoc('SELECT id, name FROM schooltype');
                $schoolsByType = RedBeanPHP\R::getAssoc(
                    'SELECT school.schooltype_id, COUNT(*) AS cnt '
                    . 'FROM school '
                    . 'GROUP BY school.schooltype_id'
                );
                $appFormByType = RedBeanPHP\R::getAssoc(
                    'SELECT school.schooltype_id, COUNT(DISTINCT applicationform.school_id) AS cnt '
                    . 'FROM applicationform INNER JOIN school '
                    . 'ON applicationform.school_id = school.id '
                    . 'GROUP BY school.schooltype_id'
                );
                $results = [];
                foreach ($schoolsByType as $type => $cnt) {
                    if (!isset($schoolTypes[$type])) {
                        continue;
                    }
                    $results[$type] = [
                        'type'         => $schoolTypes[$type],
                        'schools_cnt'  => (int) $cnt,
                        'appforms_cnt' => isset($appFormByType[$type]) ? (int) $appFormByType[$type] : 0,
                    ];
                }
                usort($results, function ($a, $b) {
                    return strcasecmp($a['type'], $b['type']);
                });

                return $results;
            };
        };
    });

    $events('on', 'app.bootstrap', function ($app, $c) {
        $app->get('/in_numbers', function (Slim\Http\Request $req, \Slim\Http\Response $res) use ($c) {
            try {
                $view = $c->get('view');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
                $inNumbersFunction = $c->get('in_numbers_by_school_type');
                $schoolTypes = $inNumbersFunction();
                $totals = array_reduce($schoolTypes, function ($result, $type) {
                    $result['schools_total'] +=  $type['schools_cnt'];
                    $result['appforms_total'] +=  $type['appforms_cnt'];

                    return $result;
                }, ['schools_total' => 0, 'appforms_total' => 0]);

                return $view->render($res, 'in_numbers/index.twig', [
                    'school_types' => $schoolTypes,
                    'totals'       => $totals,
                ]);
            } catch (\Exception $ex) {
                $c->get('logger')->error(sprintf('Exception: %s', $ex->getMessage()), ['file' => __FILE__, 'line' => __LINE__]);

                return [];
            }

        })->setName('in_numbers');
    });

    $events('on', 'app.bootstrap', function ($app, $c) {
        $router = $c['router'];
        $route = $router->getNamedRoute('index');
        $route->add(function (Slim\Http\Request $req, Slim\Http\Response $res, callable $next) use ($c) {
            try {
                $view = $c->get('view');
                $inNumbersFunction = $c->get('in_numbers_totals');
                list($total_schools, $total_app_forms) = array_values($inNumbersFunction());
                $view['total_schools'] = $total_schools;
                $view['total_app_forms'] = $total_app_forms;
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/../application/templates', 'application');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
            } catch (\Exception $ex) {
                $c->get('logger')->error(sprintf('Exception: %s', $ex->getMessage()), ['file' => __FILE__, 'line' => __LINE__]);
            }

            return $next($req, $res);
        });

    }, -10);
};
