<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\User\Models\User;
use App\Base\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthRepository extends BaseRepository
{

    public function registerUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->fetchAllAssociativeDTO($user);
    }

    public function attemptLogin(array $credentials): ?string
    {
        return auth()->attempt($credentials);
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function refreshToken(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }
}
