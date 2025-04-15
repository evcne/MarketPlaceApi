<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserRepository;
use App\Helpers\Helper;
use App\Base\BaseResponse;
use App\Base\BaseService;



class UserService extends BaseService
{
    protected $userRepository;
    protected $baseResponse;


    public function __construct(UserRepository $userRepository, Helper $helper, BaseResponse $baseResponse)
    {
        parent::__construct($helper);
        $this->userRepository = $userRepository;
        $this->baseResponse = $baseResponse;

    }

    public function getAllUsers()
    {

        $user = $this->getUserWithAutoRefresh();
        if (!$user) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $userList = $this->userRepository->getAllUsers();

        if(!$this->helper->checkIfExist($userList))
            return $this->baseResponse->readFailedResponse(null);


        return $this->baseResponse->readSuccessResponse($userList);
    }

    public function createUser($data)
    {
        $newUser = $this->userRepository->createUser($data);

        if(!$this->helper->checkIfExist($newUser))
            return $this->baseResponse->createFailedMessageResponse($newUser, '');


        return $this->baseResponse->createSuccessResponse($newUser);
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
