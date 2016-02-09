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

    $container['autoloader']->addPsr4('SchAutoCreate\\', __DIR__ . '/src');

    $events = $container['events'];

    $container[SchAutoCreate\Listener\User::class] = function ($c) {
        return new SchAutoCreate\Listener\User($c['logger']);
    };

    $events('on', 'authenticate.success', function ($stop, $identity) use ($container) {
        $listener = $container[SchAutoCreate\Listener\User::class];

        return $listener($stop, $identity);
    });
};
