<?php return [
    // Slim Settings
    'determineRouteBeforeAppMiddleware' => false,
    'displayErrorDetails'               => false,
    // View settings
    'view' => [
        'template_path' => __DIR__ . '/../app/templates',
        'twig'          => [
            'cache'       => __DIR__ . '/../data/cache/twig',
            'debug'       => true,
            'auto_reload' => true,
        ],
    ],
    // monolog settings
    'logger' => [
        'name' => 'app',
        'path' => __DIR__ . '/../data/log/' . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] . '/' : '') . 'app.log',
    ],
];
