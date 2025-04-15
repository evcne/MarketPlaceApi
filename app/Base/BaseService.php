<?php

namespace App\Base;

use App\Helpers\Helper;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

abstract class BaseService
{
    protected Helper $helper;
    protected ?string $refreshedToken = null;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Başarılı response
     */
    protected function success($data = null, $message = 'Başarılı', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'token' => $this->refreshedToken // refresh token varsa ekle
        ], $code);
    }

    /**
     * Hatalı response
     */
    protected function error($message = 'Bir hata oluştu', $code = 500, $errors = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Otomatik token refresh ve kullanıcı çekme
     */
    protected function getUserWithAutoRefresh()
    {
        try {
            // İlk olarak mevcut token'dan kullanıcıyı almaya çalış
            $user = auth()->user();
            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            try {
                // Token süresi dolmuşsa, yenileyip kullanıcıyı tekrar al
                $newToken = auth()->refresh();
                $this->refreshedToken = $newToken;
    
                auth()->setToken($newToken);
                return auth()->user();
            } catch (\Exception $ex) {
                // Token yenilenemiyorsa kullanıcı artık logout olmuş kabul edilir
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * İstisna loglama (helper ile)
     */
    protected function logException(string $context, \Exception $e)
    {
        $this->helper->gelfOutput([
            'context' => $context,
            'exception' => $e->getMessage()
        ], false, 3);
    }
}
