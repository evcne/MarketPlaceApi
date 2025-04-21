<?php

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Modules\User\Services\UserService;
use App\Base\BaseResponse;
use App\Http\Controllers\Controller;



class UserController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected BaseResponse $baseResponse)
    {}     
    

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return $users;
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $user = $this->userService->createUser($data);
        return $user;
    }

    public function updateUserRole(Request $request)
    {
        /*$request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);*/

        $data = $request->only('company_id', 'update_user_id', 'is_vendor', 'role');
        $userUpdate = $this->userService->updateUserRole($data);
        return $userUpdate;

    }

    public function changeStatus($id)
    {
        $userChangeStatus = $this->userService->changeStatus($id);
        return $userChangeStatus;
    }
}
