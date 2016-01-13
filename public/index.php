<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

chdir(dirname(__DIR__));

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = new \Slim\App(Knlv\config_merge('config', ['global', 'local']));

require 'app/dependencies.php';
require 'app/middleware.php';
require 'app/routes.php';

$app->run();
