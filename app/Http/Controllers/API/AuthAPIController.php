<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use LdapRecord\Container;

class AuthAPIController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = $request->email;
        $password = $request->password;

        try {
            $ldap = Container::getConnection('default');

            if (! $ldap->auth()->attempt($email, $password, true)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'LDAP connection failed: ' . $e->getMessage()], 500);
        }

        // Generate JWT token only
        $token = JWTAuth::fromUser((object) ['id' => 0, 'email' => $email]);

        return response()->json([
            'message' => 'Login successful',
            'email'   => $email,
            'token'   => $token,
        ], 200);
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

