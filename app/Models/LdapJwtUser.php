<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;

class LdapJwtUser implements JWTSubject
{
    protected $ldapUser;

    // Accept the LDAP user object
    public function __construct($ldapUser)
    {
        $this->ldapUser = $ldapUser;
    }

    // Return a unique identifier for the JWT
    public function getJWTIdentifier()
    {
        return $this->ldapUser->getFirstAttribute('uid'); // or 'samaccountname'
    }

    // Return any custom claims (optional)
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Allow access to LDAP attributes like properties
    public function __get($key)
    {
        return $this->ldapUser->{$key};
    }
}
