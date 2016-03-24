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

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('SchMM\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($container) {
        $container[SchMM\FetchUnit::class] = function ($c) {
            $settings = $c['settings'];

            return new SchMM\FetchUnit(new GuzzleHttp\Client([
                'base_uri' => $settings['sch_mm']['api_url'],
                'auth'     => [
                    $settings['sch_mm']['api_user'],
                    $settings['sch_mm']['api_pass'],
                ],
            ]));
        };

        $container[SchMM\FetchUnitByMmId::class] = function ($c) {
            $settings = $c['settings'];

            return new SchMM\FetchUnitByMmId(new GuzzleHttp\Client([
                'base_uri' => $settings['sch_mm']['api_url'],
                'auth'     => [
                    $settings['sch_mm']['api_user'],
                    $settings['sch_mm']['api_pass'],
                ],
            ]));
        };
    });

};
