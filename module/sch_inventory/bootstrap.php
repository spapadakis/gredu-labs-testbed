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

    $container['autoloader']->addPsr4('SchInventory\\', __DIR__ . '/src/');

    $container['SchInventory\\Service'] = function ($c) {
        $settings = $c['settings'];

        return new SchInventory\GuzzleHttpService(
            new GuzzleHttp\Client($settings['inventory'])
        );
    };
};
