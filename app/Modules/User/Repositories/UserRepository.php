<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use App\Base\BaseRepository;
use Illuminate\Support\Facades\Hash;


class UserRepository extends BaseRepository
{
    public function getAllUsers()
    {
        return $this->fetchAllAssociativeDTO(User::all());
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->fetchAllAssociativeDTO($user);
    }
}
