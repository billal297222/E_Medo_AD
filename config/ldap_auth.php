<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LDAP Connection
    |--------------------------------------------------------------------------
    |
    | The LDAP connection that will be used for authentication.
    |
    */
    'connection' => env('LDAP_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | LDAP Authentication Provider
    |--------------------------------------------------------------------------
    |
    | Since you are not using a database, model/database syncing is disabled.
    |
    */
    'provider' => [
        'users' => [
            'model' => null,      // No Eloquent model required
            'rules' => [],        // LDAP-specific rules can go here if needed
            'database' => null,   // Disable DB sync
        ],
    ],

];
