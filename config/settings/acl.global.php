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
                ['/user/login', ['guest'], ['get', 'post']],
                ['/user/logout', ['user'], ['get']],
                ['/school', ['guest', 'user'], ['get']],
                ['/school/labs', ['guest', 'user'], ['get']],
                ['/school/staff', ['guest', 'user'], ['get']],
                ['/school/assets', ['guest', 'user'], ['get']],
            ],
        ],
    ],
];
