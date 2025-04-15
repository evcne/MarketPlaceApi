<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
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
