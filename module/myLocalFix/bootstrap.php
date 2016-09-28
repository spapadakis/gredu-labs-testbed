<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

use Slim\Container;

return function (Slim\App $app) {

    $container = $app->getContainer();
    $events = $container->get('events');

    $events('on', 'app.services', function (Container $container) {
        // echo var_export(RedBeanPHP\R::getPDO());
        if (RedBeanPHP\R::getPDO() !== null) {
            RedBeanPHP\R::getPDO()->exec(' SET sql_mode=\'\'');
            $container['logger']->debug('Run local fix for sql_mode');
        }
    }, -10000);
};
