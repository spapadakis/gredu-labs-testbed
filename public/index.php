<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
chdir(dirname(__DIR__));

if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

$autoloader = require 'vendor/autoload.php';

$appConfig = include 'config/app.config.php';
if (is_readable('config/dev.config.php')) {
    $devConfig                 = include 'config/dev.config.php';
    $appConfig['modules']      = array_unique(array_merge($appConfig['modules'], $devConfig['modules']));
    $appConfig['cache_config'] = isset($devConfig['cache_config']) ? $devConfig['cache_config'] : $appConfig['cache_config'];
}

$settings = [];

if ($appConfig['cache_config'] && is_readable($appConfig['cache_config'])) {
    $settings = include $appConfig['cache_config'];
} else {
    $settings = Knlv\config_merge('config/settings', ['global', 'local'], $settings);
    if ($appConfig['cache_config'] && is_writable(dirname($appConfig['cache_config']))) {
        file_put_contents(
            $appConfig['cache_config'],
            '<?php return ' . var_export($settings, true) . ';'
        );
    }
}

$app                     = new Slim\App(['settings' => $settings]);
$container               = $app->getContainer();
$container['autoloader'] = $autoloader;
$container['events']     = function ($c) {
    return 'Knlv\events';
};

array_walk($appConfig['modules'], function ($module) use ($app) {
    if (is_readable($module)) {
        call_user_func(include $module, $app);
    }
});

$events = $container['events'];
$events('trigger', 'bootstrap');

$app->run();
