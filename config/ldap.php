<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default LDAP Connection Name
    |--------------------------------------------------------------------------
    */
    'default' => env('LDAP_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | LDAP Connections
    |--------------------------------------------------------------------------
    */
  'connections' => [
    'default' => [
        'hosts' => [env('LDAP_HOST', env('Alt_LDAP_HOST'))], // correct way

        'username' => env('LDAP_USERNAME'),
        'password' => env('LDAP_PASSWORD'),
        'port' => env('LDAP_PORT', 389),
        'base_dn' => env('LDAP_BASE_DN'),
        'timeout' => env('LDAP_TIMEOUT', 5),
        'use_ssl' => env('LDAP_USE_SSL', false),
        'use_tls' => env('LDAP_USE_TLS', false),
        'use_sasl' => false,
    ],
],


    /*
    |--------------------------------------------------------------------------
    | LDAP Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('LDAP_LOGGING', true),
        'channel' => env('LOG_CHANNEL', 'stack'),
        'level' => env('LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('LDAP_CACHE', false),
        'driver' => env('CACHE_DRIVER', 'file'),
    ],

];
