<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Responses\BaseResponse;


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
