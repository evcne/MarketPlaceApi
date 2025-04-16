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
}
