<?php
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
