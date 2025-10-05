<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global SAML Settings
    |--------------------------------------------------------------------------
    */
    'strict' => true,
    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Service Provider (SP) Settings
    |--------------------------------------------------------------------------
    */
    'sp' => [
        'entityId' => 'https://emedo.com/WIN-OBH8DTQMOJH/metadata',
        'assertionConsumerService' => [
            'url' => 'https://emedo.com/WIN-OBH8DTQMOJH/saml2/acs',
        ],
        'singleLogoutService' => [
            'url' => 'https://emedo.com/WIN-OBH8DTQMOJH/saml2/sls',
        ],
        'x509cert' => '', // Optional: SP certificate if using signed requests
        'privateKey' => '', // Optional: SP private key
    ],

    /*
    |--------------------------------------------------------------------------
    | Identity Provider (IdP) Settings
    |--------------------------------------------------------------------------
    */
    'idp' => [
        'entityId' => 'https://win-obh8dtqmojh.emedo.com/adfs/services/trust',
        'singleSignOnService' => [
            'url' => 'https://win-obh8dtqmojh.emedo.com/adfs/ls/',
        ],
        'singleLogoutService' => [
            'url' => 'https://win-obh8dtqmojh.emedo.com/adfs/ls/?wa=wsignout1.0',
        ],
        'x509cert' => 'MIIC...ADFS-CERTIFICATE-HERE...', // ADFS token-signing certificate
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional: Multiple IdPs
    |--------------------------------------------------------------------------
    */
    'idpNames' => ['adfs'],

    /*
    |--------------------------------------------------------------------------
    | Routes & Middleware
    |--------------------------------------------------------------------------
    */
    'useRoutes' => true,
    'routesPrefix' => '/saml2',
    'routesMiddleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    */
    'loginRoute' => '/dashboard', // After successful login
    'logoutRoute' => '/login',    // After logout
    'errorRoute' => '/login?error=saml', // On SAML error

    /*
    |--------------------------------------------------------------------------
    | Miscellaneous
    |--------------------------------------------------------------------------
    */
    'retrieveParametersFromServer' => false,
    'proxyVars' => false, // true if behind a proxy/load balancer
    // 'saml2_controller' => '', // Optional: Custom controller
];
