<?php return [
    'navigation' => [
        'main' => [
            [
                'label' => 'Αρχική',
                'route' => 'index',
            ],
            [
                'label' => 'Σύνδεση',
                'route' => 'user.login',
            ],
            [
                'label' => 'Αποσύνδεση',
                'route' => 'user.logout',
                'id'    => 'nav-logout',
            ],
        ],
    ],
];
