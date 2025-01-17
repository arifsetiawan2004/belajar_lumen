<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'user'
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'user',
            'hash' => false,
        ],
    ],

    'providers' => [
        'user' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
        ];