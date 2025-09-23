<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\UserApi;
use Illuminate\Support\Facades\Hash;

class AuthAPIControllerO extends Controller
{
    use ApiResponse;

    public function userLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->login;
        $password   = $request->password;

        // Determine if login is email or phone
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $user=UserApi::where($loginField, $loginInput)->where('admin',0)->first();

        if(!$user){
            return $this->error('', 'Invalid email or phone number', 401);
        }

        if(!Hash::check($password,$user->password)){
            return $this->error('', 'Invalid Password', 401);
        }

        $credentials = [
            $loginField => $loginInput,
            'password'  => $password,
            'admin'     => 0,
        ];

        if (! $token = auth('api')->attempt($credentials)) {
    return $this->error('', 'Invalid credentials', 401);
}
        $data = [
            'token'      => $token,
            'token_type' => 'bearer',
            // 'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];

        return $this->success($data, 'Login successful', 200);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return $this->success('', 'Logged out successfully', 200);
    }

    public function refresh()
    {
        $data = [
            'token'      => JWTAuth::refresh(JWTAuth::getToken()),
            'token_type' => 'bearer',
            // 'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];

        return $this->success($data, 'Token refreshed', 200);
    }

    public function me()
    {
        return $this->success(JWTAuth::user(), 'User info', 200);
    }
}
