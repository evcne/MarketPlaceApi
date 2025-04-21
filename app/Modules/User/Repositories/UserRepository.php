<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use App\Base\BaseRepository;
use Illuminate\Support\Facades\Hash;


class UserRepository extends BaseRepository
{
    public function getAllUsers($isAdminRole, $userId)
    {
        if ($isAdminRole) {
            $userList = User::all();
        } else {
            $userList = User::where('id', $userId)->get();
        }

        return $this->fetchAllAssociativeDTO($userList);
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

    public function updateUserRole($data)
    {
        $users = User::where('company_id', $data['company_id'])->get();
        foreach ($users as $user) {
            $user->update($data);

        }        
        return $this->fetchAllAssociativeDTO($user);
    }

    public function changeStatus($id)
    {
       
        $id = (array) $id;

        if (count($id)) {
            User::whereIn('id', $id)->update(['status' => false]);
            return $this->fetchAllAssociativeDTO(User::whereIn('id', $id)->get());
        }

        return [];

    }
}
