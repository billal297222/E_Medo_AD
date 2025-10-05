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

use Aacotroneo\Saml2\Saml2Auth;






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




// public function login(Request $request)
// {
//     $request->validate([
//         'uid' => 'required|string',
//         'password' => 'required|string',
//     ]);

//     // LDAP থেকে user খুঁজে বের করো
//     $ldapUser = LdapUser::where('uid', $request->uid)->first();
//     if (!$ldapUser) {
//         //return response()->json(['message' => 'Invalid user'], 401);
//          return $this->error('', 'Invalid User', 401);
//     }

//     $dn = $ldapUser->getDn();

//     try {
//         $connection = Container::getConnection();
//         $connection->auth()->attempt($dn, $request->password, true);
//     } catch (\LdapRecord\Auth\BindException $e) {
//         //return response()->json(['message' => 'Invalid credentials'], 401);
//         return $this->error('', 'Invalid User', 401);
//     }

//     // Local Eloquent user
//     $localUser = UserApi::firstOrCreate(
//         ['uid' => $ldapUser->getFirstAttribute('uid')],
//         [
//             'name' => $ldapUser->getFirstAttribute('cn'),
//             'email' => $ldapUser->getFirstAttribute('mail') ?? $ldapUser->getFirstAttribute('uid') . '@example.com',
//             'password' => bcrypt('dummy'),
//         ]
//     );

//     // JWT generate
//     $token = JWTAuth::fromUser($localUser);

//     // HTTP-only cookie
//     $userData = [
//     'uid' => $localUser->uid,
//     'name' => $localUser->name,
//     'email' => $localUser->email,
//     'token' => $token
// ];

// return $this->success($userData, 'login success', 200)->cookie('sso_token', $token, 100 * 365 * 24 * 60, null, null, false, true); // 100 years

// }




//  public function validateToken(Request $request)
//     {
//         $token = $request->bearerToken() ?: $request->cookie('sso_token');

//         if (!$token) {
//             //return response()->json(['success' => false, 'message' => 'No token provided'], 401);
//             return $this->error('', 'No token provided', 401);
//         }

//         try {
//             $user = JWTAuth::setToken($token)->toUser();
//             //return response()->json(['success' => true, 'user' => $user]);
//             return $this->success($user, 'token validate successfully', 200);
//         } catch (\Exception $e) {
//             //return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
//             return $this->error('', 'Invalid token', 401);
//         }
//     }







// public function ssoLogin(Request $request)
//     {
//         $token = $request->cookie('sso_token') ?: $request->bearerToken();

//         if (!$token) {
//             return $this->error('', 'Invalid token', 401, [
//                 'redirect' => config('app.url').'/login'
//             ]);

//         }

//         // Validate token internally
//         try {
//             $user = JWTAuth::setToken($token)->toUser();
//         } catch (\Exception $e) {
//             return $this->error('', 'Invalid token', 401, [
//                 'redirect' => config('app.url').'/login'
//             ]);
//         }

//         // Local SP user creation/update
//         $localUser = UserApi::firstOrCreate(
//             ['uid' => $user->uid],
//             [
//                 'name' => $user->name,
//                 'email' => $user->email,
//                 'password' => bcrypt('dummy')
//             ]
//         );

//         Auth::loginUsingId($localUser->id);

//         //return response()->json(['success'=>true,'user'=>$user]);
//         return $this->success($user, 'sso login success', 200);
//     }



// public function logout(Request $request)
// {
//     // Invalidate JWT if present
//     try {
//         $token = $request->cookie('sso_token') ?: $request->bearerToken();
//         if ($token) {
//             JWTAuth::setToken($token)->invalidate();
//         }
//     } catch (\Exception $e) {
//         // Token already invalid or missing, ignore
//     }

//     // Logout from Laravel session
//     Auth::logout();

//     // Forget the SSO cookie
//     $cookie = Cookie::forget('sso_token');

//     // Return JSON response with cookie removed
//     return $this->success('', 'Logged out successfully', 200)->withCookie($cookie);
// }











// multiple browser


// public function login(Request $request)
//  {
//         $request->validate([
//             'uid' => 'required|string',
//             'password' => 'required|string',
//         ]);

//         // LDAP user খুঁজে বের করা
//         $ldapUser = LdapUser::where('uid', $request->uid)->first();
//         if (!$ldapUser) {
//             return $this->error('', 'Invalid User', 401);
//         }

//         $dn = $ldapUser->getDn();

//         try {
//             $connection = Container::getConnection();
//             $connection->auth()->attempt($dn, $request->password, true);
//         } catch (\LdapRecord\Auth\BindException $e) {
//             return $this->error('', 'Invalid credentials', 401);
//         }

//         // Local SP user creation/update
//         $localUser = UserApi::firstOrCreate(
//             ['uid' => $ldapUser->getFirstAttribute('uid')],
//             [
//                 'name'  => $ldapUser->getFirstAttribute('cn'),
//                 'email' => $ldapUser->getFirstAttribute('mail') ?? $ldapUser->getFirstAttribute('uid').'@example.com',
//                 'password' => bcrypt('dummy'),
//             ]
//         );

//         // Redirect user to ADFS for SSO token
//         $adfsLoginUrl = config('sso.adfs_login_url'); // ex: https://adfs.company.local/adfs/ls/
//         return $this->success([
//             'message' => 'Redirect to ADFS for SSO',
//             'idp_url' => $adfsLoginUrl
//         ], 'AD login success', 200);
//     }



// public function ssoLogin(Request $request)
// {
//     // 1. Receive IdP-issued token (OIDC ID token)
//    $idToken = $request->bearerToken() ?: $request->input('id_token');
//         if (!$idToken) {
//             return $this->error('', 'No token provided', 401, [
//                 'redirect' => config('app.url').'/login'
//             ]);
//         }

//         try {
//             $userData = $this->validateIdPToken($idToken);
//         } catch (\Exception $e) {
//             return $this->error('', 'Invalid token', 401, [
//                 'redirect' => config('app.url').'/login'
//             ]);
//         }

//         // Local SP user creation/update
//         $localUser = UserApi::firstOrCreate(
//             ['uid' => $userData['uid']],
//             [
//                 'name'  => $userData['name'],
//                 'email' => $userData['email'],
//                 'password' => bcrypt('dummy'),
//             ]
//         );

//         Auth::loginUsingId($localUser->id);

//         // Issue SP JWT for SPA/API
//         $token = JWTAuth::fromUser($localUser);

//         return $this->success([
//             'user'  => $userData,
//             'token' => $token
//         ], 'SSO login success', 200)->cookie('sso_token', $token, 60*24*7, null, null, false, true);
//     }
// // Example helper for validating IdP-issued OIDC token
//  private function validateIdPToken($token)
//     {
//         $jwksUrl = config('sso.adfs_jwks_url'); // ex: https://adfs.company.local/federationmetadata/2007-06/federationmetadata.xml
//         $jwks = json_decode(Http::get($jwksUrl)->body(), true);

//         // Decode & verify JWT using RS256 public key
//         $decoded = \Firebase\JWT\JWT::decode(
//             $token,
//             new \Firebase\JWT\Key($jwks['keys'][0]['x5c'][0], 'RS256')
//         );

//         return [
//             'uid'   => $decoded->sub,
//             'name'  => $decoded->name,
//             'email' => $decoded->email
//         ];
//     }



//     public function logout(Request $request)
// {
//     // 1. JWT invalidate করা
//     try {
//         $token = $request->cookie('sso_token') ?: $request->bearerToken();
//         if ($token) {
//             JWTAuth::setToken($token)->invalidate();
//         }
//     } catch (\Exception $e) {
//         // Token ইতিমধ্যে invalid বা missing, ignore
//     }

//     // 2. Laravel session clear
//     session()->forget('idp_user_id');
//     Auth::logout();

//     // 3. HTTP-only cookie মুছে দেওয়া
//     $cookie = \Illuminate\Support\Facades\Cookie::forget('sso_token');

//     return response()->json([
//         'success' => true,
//         'message' => 'Logged out successfully'
//     ])->withCookie($cookie);
// }











    // public function me(Request $request)
    // {
    //     try {
    //         $user = JWTAuth::parseToken()->authenticate();
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Invalid or expired token'], 401);
    //     }

    //     return response()->json(['user' => $user]);



    // }

//     public function logout(Request $request)
//     {
//         try {
//             JWTAuth::invalidate(JWTAuth::getToken());
//         } catch (\Exception $e) {
//             // Token already invalid
//         }

//         return response()->json(['message' => 'Logged out successfully']);
//     }


public function ssoLogin(Saml2Auth $saml2Auth)
    {
        return $saml2Auth->login();
    }

 public function samlAcs(Saml2Auth $saml2Auth)
    {
        $errors = $saml2Auth->acs();
        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 400);
        }

        $samlUser = $saml2Auth->getSaml2User();

        $uid   = $samlUser->getUserId();
        $name  = $samlUser->getAttributes()['displayName'][0] ?? $uid;
        $email = $samlUser->getAttributes()['mail'][0] ?? $uid . '@example.com';

        // Store SSO info in session only (no local DB)
        session([
            'sso_user' => [
                'uid' => $uid,
                'name' => $name,
                'email' => $email,
            ]
        ]);

        // Optionally mark as "logged in" in session
        session(['sso_logged_in' => true]);

        return response()->json([
            'success' => true,
            'user' => session('sso_user'),
            'message' => 'SSO login success'
        ], 200);
    }

    /**
     * SLS endpoint: logout from IdP and session
     */
    public function samlSls(Saml2Auth $saml2Auth, Request $request)
    {
        $saml2Auth->sls($request, true); // logout from IdP

        // Clear session
        session()->forget(['sso_user', 'sso_logged_in']);
        Auth::logout(); // optional if you’re not using Laravel Auth

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }




 }
