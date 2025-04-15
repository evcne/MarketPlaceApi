<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Kayıt başarılı', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Geçersiz giriş bilgileri'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function refresh(Request $request)
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
    }

    public function logout()
    {
        /*try {
            auth()->logout();
            return response()->json(['message' => 'Çıkış yapıldı']);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to logout, please try again later'], 500);
        }

        
        try {
            auth()->logout(); // Mevcut token'ı geçersiz kılar (blacklist'e ekler)
            return response()->json(['message' => 'Başarıyla çıkış yapıldı.']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'error' => 'Çıkış yapılamadı.',
                'detail' => $e->getMessage()
            ], 500);
        }*/

        /*try {

            // Eğer bir token yoksa, hata dön
            if (! $token = JWTAuth::getToken()) {
                    return response()->json(['error' => 'Token bulunamadı, çıkış yapamazsınız.'], 400);
            }
                
            // JWT Auth ile mevcut token'ı geçersiz kılma
            JWTAuth::invalidate(JWTAuth::getToken());
    
            return response()->json(['message' => 'Başarıyla çıkış yapıldı.']);
        } catch (JWTException $e) {
            // Eğer hata alırsanız, burada hata detaylarıyla birlikte yanıt veriyoruz
            return response()->json([
                'error' => 'Çıkış yapılamadı.',
                'detail' => $e->getMessage()
            ], 500);
        }*/

        try {
            auth()->logout(); // token blacklist'e alınır
            return response()->json(['message' => 'Başarıyla çıkış yapıldı.']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Çıkış yapılamadı.', 'detail' => $e->getMessage()], 500);
        }
        
        
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'refresh_token' => JWTAuth::getToken(), // Yeni oluşturulan refresh token
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
