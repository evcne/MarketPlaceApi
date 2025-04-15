<?php

namespace App\Http\Controllers;

use App\Modules\User\Services\UserService;

class DebugController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function checkToken()
    {
        return $this->userService->debugTokenInfo();
    }
}
