<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RefreshJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Token hala geçerli mi?
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            try {
                $newToken = JWTAuth::parseToken()->refresh();
                JWTAuth::setToken($newToken)->toUser();

                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer ' . $newToken);
                return $response;

            } catch (\Exception $ex) {
                return response()->json([
                    'error' => 'Token yenilenemedi',
                    'detail' => $ex->getMessage()
                ], 401);
            }
        }

        return $next($request);
    }
    
}
