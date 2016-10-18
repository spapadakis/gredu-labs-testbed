<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use GrEduLabs\OpenData\Action;
use GrEduLabs\OpenData\Service;

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
    $csv_export_types = [
        'schools', 'labs', 'assets', 'software',
        'appforms', 'appnewforms',
        'appforms_items', 'newapplication'
    ];

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\OpenData\\', __DIR__ . '/src/');
    });

    /**
     * Adds api routes to acl 
     */
    $events('on', 'app.services', function ($container) use ($csv_export_types) {
        $acl = $container['settings']['acl'];
        $acl['guards']['routes'] = array_merge($acl['guards']['routes'], [
            ['/open-data/api', ['guest', 'user'], ['get']],
            ['/open-data/api/schtest', ['guest', 'user'], ['get']], // TODO 
        ]);
        foreach ($csv_export_types as $csv_export_type) {
            $acl['guards']['routes'][] = ["/open-data/api/{$csv_export_type}", ['guest', 'user'], ['get']];
        }
        $acl['guards']['routes'][] = ["/open-data/api2", ['guest', 'user'], ['get']];
        $container['settings']->set('acl', $acl);
    });

    $events('on', 'app.services', function ($container) use ($csv_export_types) {
        // root api page handler 
        $container[GrEduLabs\OpenData\Service\IndexProvider::class] = function ($c) {
            $settings = $c->get('settings');
            return new GrEduLabs\OpenData\Service\IndexProvider((string) $settings['api_doc_url'], $c['router']);
        };
        $container[GrEduLabs\OpenData\Action\Index::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\Index(
                $c, $c->get(GrEduLabs\OpenData\Service\IndexProvider::class), true
            );
        };

        // schools api page handler -- DEMO TODO CHANGE
        $container[GrEduLabs\OpenData\Service\SchoolProvider::class] = function ($c) {
            return new GrEduLabs\OpenData\Service\SchoolProvider();
        };
        $container[GrEduLabs\OpenData\Action\School::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\School(
                $c, $c->get(GrEduLabs\OpenData\Service\SchoolProvider::class), false
            );
        };

        // new forms api page handler -- DEMO TODO CHANGE
        $container[GrEduLabs\OpenData\Service\AppNewFormProvider::class] = function ($c) {
            return new GrEduLabs\OpenData\Service\AppNewFormProvider($c);
        };
        $container[GrEduLabs\OpenData\Action\AppNewForm::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\AppNewForm(
                $c, $c->get(GrEduLabs\OpenData\Service\AppNewFormProvider::class), false
            );
        };

        // towards a generic wrapper for existing csv exports 
        // -- DEMO TODO CHANGE
        foreach ($csv_export_types as $csv_export_type) {
            $container["{$csv_export_type}_provider"] = function ($c) use ($csv_export_type) {
                return new GrEduLabs\OpenData\Service\CsvExportDataProvider($c, $csv_export_type);
            };
            $container["{$csv_export_type}_action"] = function ($c) use ($csv_export_type) {
                return new GrEduLabs\OpenData\Action\ApiAction(
                    $c, $c->get("{$csv_export_type}_provider"), false
                );
            };
        }

        $container['school_test_provider'] = function ($c) {
            $dataProvider = new GrEduLabs\OpenData\Service\RedBeanQueryPagedDataProvider();
            $dataProvider->setPagesize(10);
            $dataProvider->setPage(1);
            $dataProvider->setQuery('select id, name from school');
            return $dataProvider;
        };
        $container[GrEduLabs\OpenData\Action\SchoolTest::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\SchoolTest(
                $c, $c->get('school_test_provider'), true
            );
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) use ($csv_export_types) {

        $app->get('/open-data', function (Request $req, Response $res) use ($c) {
            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            return $view->render($res, 'open_data/index.twig');
        })->setName('open_data');

        /**
         * Define api routes. Each route is handled by a devoted class.
         */
        $app->group('/open-data/api', function () use ($csv_export_types) {
            $this->get('', Action\Index::class)
                ->setName('open_data.api');
            $this->get('/schtest', Action\SchoolTest::class)
                ->setName('open_data.api.schtest');
//            $this->get('/schools', Action\School::class)
//                ->setName('open_data.api.school');
//            $this->get('/appnewforms', Action\AppNewForm::class)
//                ->setName('open_data.api.appnewforms');
//            $this->get("/appnewforms", "appnewforms_action");
            foreach ($csv_export_types as $csv_export_type) {
                $this->get("/{$csv_export_type}", "{$csv_export_type}_action");
            }
//            $this->get('/appnewforms', 'appnewforms_action')
//                ->setName('open_data.api.appnewforms');
//            $this->get('/newapplication', 'newapplication_action')
//                ->setName('open_data.api.newapplication');
        });
    });
};
