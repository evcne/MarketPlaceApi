<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Repositories\AuthRepository;
use App\Helpers\Helper;
use App\Base\BaseResponse;
use App\Base\BaseService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;



class AuthService extends BaseService
{
    protected $authRepository;
    protected $baseResponse;


    public function __construct(AuthRepository $authRepository, Helper $helper, BaseResponse $baseResponse)
    {
        parent::__construct($helper);
        $this->authRepository = $authRepository;
        $this->baseResponse = $baseResponse;

    }

    public function registerUser($data)
    {
        $newUser = $this->authRepository->registerUser($data);

        if(!$this->helper->checkIfExist($newUser))
            return $this->baseResponse->createFailedMessageResponse($newUser, '');


        return $this->baseResponse->createSuccessResponse($newUser);
    }

    public function login(array $credentials)
    {
        $token = $this->authRepository->attemptLogin($credentials);

        if (!$token) {
            return $this->baseResponse->authFailedResponse(null);
        }

        return $this->respondWithToken($token);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'refresh_token' => JWTAuth::getToken(), // Yeni oluşturulan refresh token
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        try {
            $this->authRepository->logout();
            return $this->baseResponse->authLogoutSuccessResponse(null);
        } catch (JWTException $e) {
            return $this->baseResponse->authLogoutFailedResponse(null);
        }
    }

    public function refreshToken()
    {
        try {
            $newToken = $this->authRepository->refreshToken();

            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        } catch (TokenInvalidException $e) {
            return $this->baseResponse->authTokenExpiredResponse(null);
        }
    }

    public function debugTokenInfo()
    {
        $user = $this->getUserWithAutoRefresh();

        if (!$user) {
            return $this->error('Token geçersiz veya refresh edilemedi.', 401);
        }

        return $this->success([
            'user' => $user,
            'refreshed_token' => $this->refreshedToken ?? 'Token hâlâ geçerli, refresh edilmedi'
        ], 'Token durumu');
    }

}
