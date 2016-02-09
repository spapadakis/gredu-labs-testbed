<?php

return [
    'determineRouteBeforeAppMiddleware' => true,
    'displayErrorDetails'               => true,
    'db'                                => [
        'dsn'     => 'sqlite:' . __DIR__ . '/../data/tmp/db.sq3',
        'user'    => null,
        'pass'    => null,
        'options' => [

        ],
    ],
];
