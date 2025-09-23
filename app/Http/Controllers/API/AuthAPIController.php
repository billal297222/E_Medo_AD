<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\UserApi;
use LdapRecord\Container;
use App\Models\LdapJwtUser;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
// use LdapRecord\Models\ActiveDirectory\User;
use App\Http\Controllers\Controller;
use LdapRecord\Laravel\Auth as LdapAuth;
use LdapRecord\Models\OpenLDAP\User as LdapUser;

class AuthAPIController extends Controller
{
    use ApiResponse;

  public function login(Request $request)
{
    $request->validate([
        'uid' => 'required|string',
        'password' => 'required|string',
    ]);

    // Find LDAP user by uid
    $ldapUser = LdapUser::where('uid', $request->uid)->first();
    if (!$ldapUser) {
        return $this->error('', 'Invalid user', 401);
    }

    // Get DN of the user
    $dn = $ldapUser->getDn();

    // Try to bind with DN and password
    try {
        $connection = Container::getConnection(); // default LDAP connection
        $connection->auth()->attempt($dn, $request->password, $bindAsUser = true);
    } catch (\LdapRecord\Auth\BindException $e) {
        return $this->error('', 'Invalid credentials', 401);
    }

    // Create or get local Eloquent user for JWT
    $localUser = UserApi::firstOrCreate(
        ['uid' => $ldapUser->getFirstAttribute('uid')],
        [
            'name' => $ldapUser->getFirstAttribute('cn'),
            'email' => $ldapUser->getFirstAttribute('mail') ?? $ldapUser->getFirstAttribute('uid') . '@example.com',
            'password' => bcrypt('dummy'), // Not actually used
        ]
    );

    // Generate JWT token from local Eloquent user
    $token = JWTAuth::fromUser($localUser);

    $data = [
        'token' => $token,
        'token_type' => 'bearer',
        'user' => [
            'name' => $localUser->name,
            'uid' => $localUser->uid,
            'email' => $localUser->email,
        ]
    ];

    return $this->success($data, 'Login successful', 200);
}


    public function me(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
            // Token already invalid
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
