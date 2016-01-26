<?php return [
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
                ['/user/login-sso', ['guest'], ['get']],
                ['/user/logout', ['user'], ['get']],
            ],
        ],
    ],
];
