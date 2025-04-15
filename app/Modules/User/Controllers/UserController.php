<?php

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Modules\User\Services\UserService;
use App\Base\BaseResponse;
use App\Http\Controllers\Controller;



class UserController extends Controller
{
    protected $userService;
    private $baseResponse;


    public function __construct(UserService $userService, BaseResponse $baseResponse)
    {
        $this->userService = $userService;
        $this->baseResponse = $baseResponse;
    }

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
