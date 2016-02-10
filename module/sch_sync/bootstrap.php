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

    $container['autoloader']->addPsr4('SchSync\\', __DIR__ . '/src');

    $container[SchSync\Listener\CreateSchool::class] = function ($c) {
        return new SchSync\Listener\CreateSchool($c['ldap'], $c[SchMM\FetchUnit::class]);
    };

    $events = $container['events'];

    $events('on', 'authenticate.success', function ($stop, $identity) use ($container) {
        $listener = $container[SchSync\Listener\CreateSchool::class];
        $listener($stop, $identity);
    }, 20);
};
