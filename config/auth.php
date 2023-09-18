<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    'admin'=>[
        'driver'=>'eloquent',
        'model'=>App\Models\Admin::class,
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins'=>[
            'driver'=>'eloquent',
            'model'=>App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins'=>[
            'driver'=>'eloquent',
            'model'=>App\Models\Admin::class,
        ],
        
    ],

    'password_timeout' => 10800,
];
