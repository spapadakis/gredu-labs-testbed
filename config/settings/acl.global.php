<?php

return [
    'acl' => [
        'default_role' => 'guest',
        'roles'        => [
            'guest' => [],
            'user'  => [],
            'admin' => ['user'],
        ],
        'resoures' => [],
        'guards'   => [
            'resources' => [],
            'callables' => [],
            'routes'    => [
                ['/', ['guest', 'user'], ['get']],
               
                ['/school', ['guest', 'user'], ['get']],
                ['/school/labs', ['guest', 'user'], ['get']],
                ['/school/staff', ['guest', 'user'], ['get']],
                ['/school/assets', ['guest', 'user'], ['get']],
            ],
        ],
    ],
];
