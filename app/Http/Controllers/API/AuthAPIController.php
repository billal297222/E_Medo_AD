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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


use Illuminate\Support\Facades\Cookie;
use LdapRecord\Laravel\Auth as LdapAuth;
use LdapRecord\Models\OpenLDAP\User as LdapUser;




class AuthAPIController extends Controller
{
    use ApiResponse;

//   public function login(Request $request)
// {
//     $request->validate([
//         'uid' => 'required|string',
//         'password' => 'required|string',
//     ]);

//     // Find LDAP user by uid
//     $ldapUser = LdapUser::where('uid', $request->uid)->first();
//     if (!$ldapUser) {
//         return $this->error('', 'Invalid user', 401);
//     }

//     // Get DN of the user
//     $dn = $ldapUser->getDn();

//     // Try to bind with DN and password
//     try {
//         $connection = Container::getConnection(); // default LDAP connection
//         $connection->auth()->attempt($dn, $request->password, $bindAsUser = true);
//     } catch (\LdapRecord\Auth\BindException $e) {
//         return $this->error('', 'Invalid credentials', 401);
//     }

//     // Create or get local Eloquent user for JWT
//     $localUser = UserApi::firstOrCreate(
//         ['uid' => $ldapUser->getFirstAttribute('uid')],
//         [
//             'name' => $ldapUser->getFirstAttribute('cn'),
//             'email' => $ldapUser->getFirstAttribute('mail') ?? $ldapUser->getFirstAttribute('uid') . '@example.com',
//             'password' => bcrypt('dummy'), // Not actually used
//         ]
//     );

//     // Generate JWT token from local Eloquent user
//     $token = JWTAuth::fromUser($localUser);

//     $data = [
//         'token' => $token,
//         'token_type' => 'bearer',
//         'user' => [
//             'name' => $localUser->name,
//             'uid' => $localUser->uid,
//             'email' => $localUser->email,
//         ]
//     ];

//     return $this->success($data, 'Login successful', 200);
// }




public function login(Request $request)
{
    $request->validate([
        'uid' => 'required|string',
        'password' => 'required|string',
    ]);

    // LDAP থেকে user খুঁজে বের করো
    $ldapUser = LdapUser::where('uid', $request->uid)->first();
    if (!$ldapUser) {
        return response()->json(['message' => 'Invalid user'], 401);
    }

    $dn = $ldapUser->getDn();

    try {
        $connection = Container::getConnection();
        $connection->auth()->attempt($dn, $request->password, true);
    } catch (\LdapRecord\Auth\BindException $e) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Local Eloquent user
    $localUser = UserApi::firstOrCreate(
        ['uid' => $ldapUser->getFirstAttribute('uid')],
        [
            'name' => $ldapUser->getFirstAttribute('cn'),
            'email' => $ldapUser->getFirstAttribute('mail') ?? $ldapUser->getFirstAttribute('uid') . '@example.com',
            'password' => bcrypt('dummy'),
        ]
    );

    // JWT generate
    $token = JWTAuth::fromUser($localUser);

    // HTTP-only cookie
    $userData = [
    'uid' => $localUser->uid,
    'name' => $localUser->name,
    'email' => $localUser->email,
    'token' => $token
];

return $this->success($userData, 'SSO login success', 200)->cookie('sso_token', $token, 100 * 365 * 24 * 60, null, null, false, true); // 100 years

}




 public function validateToken(Request $request)
    {
        $token = $request->bearerToken() ?: $request->cookie('sso_token');

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'No token provided'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->toUser();
            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        }
    }







public function ssoLogin(Request $request)
    {
        $token = $request->cookie('sso_token') ?: $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'redirect' => config('app.url').'/login'
            ], 401);
        }

        // Validate token internally
        try {
            $user = JWTAuth::setToken($token)->toUser();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'redirect' => config('app.url').'/login'
            ], 401);
        }

        // Local SP user creation/update
        $localUser = UserApi::firstOrCreate(
            ['uid' => $user->uid],
            [
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt('dummy')
            ]
        );

        Auth::loginUsingId($localUser->id);

        //return response()->json(['success'=>true,'user'=>$user]);
        return $this->success($user, 'sso login success', 200);
    }



public function logout(Request $request)
{
    // Invalidate JWT if present
    try {
        $token = $request->cookie('sso_token') ?: $request->bearerToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        }
    } catch (\Exception $e) {
        // Token already invalid or missing, ignore
    }

    // Logout from Laravel session
    Auth::logout();

    // Forget the SSO cookie
    $cookie = Cookie::forget('sso_token');

    // Return JSON response with cookie removed
    return $this->success('', 'Logged out successfully', 200)->withCookie($cookie);
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

//     public function logout(Request $request)
//     {
//         try {
//             JWTAuth::invalidate(JWTAuth::getToken());
//         } catch (\Exception $e) {
//             // Token already invalid
//         }

//         return response()->json(['message' => 'Logged out successfully']);
//     }




 }
