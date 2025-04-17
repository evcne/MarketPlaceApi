<?php

namespace App\Modules\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Modules\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Base\BaseResponse;


class AuthController extends Controller
{

    public function __construct(
        protected AuthService $authService,
        protected BaseResponse $baseResponse)
    {}  


    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $user = $this->authService->registerUser($data);
        return $user;
    }

    public function login(Request $request)
    {
        return $this->authService->login($request->only('email', 'password'));
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function refresh(Request $request)
    {
        return $this->authService->refreshToken();
    }

    /*public function refresh(Request $request)
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
    
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'error' => 'Token geçersiz veya süresi dolmuş.'
            ], 401);
        }
    }*/
}
