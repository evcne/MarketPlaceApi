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

        $isAdminRole = false;
        if($user->role == $this->helper::USER_ROLE_ADMIN || 
        $user->role == $this->helper::USER_ROLE_SUPER_ADMIN) {
            $isAdminRole = true;
        }

        $userList = $this->userRepository->getAllUsers($isAdminRole, $user->id);

        if(!$this->helper->checkIfExist($userList))
            return $this->baseResponse->readFailedResponse(null);


        return $this->baseResponse->readSuccessResponse($userList);
    }

    public function createUser($data)
    {
        $newUser = $this->userRepository->createUser($data);

        if(!$this->helper->checkIfExist($newUser))
            return $this->baseResponse->createFailedResponse(['message' => 'Kullanıcı oluşturulamadı.']);


        return $this->baseResponse->createSuccessResponse($newUser);
    }

    public function updateUserRole($data)
    {
        $newUser = $this->userRepository->updateUserRole($data);

        if(!$this->helper->checkIfExist($newUser))
            return $this->baseResponse->updateFailedResponse(['message' => 'Kullanıcı güncellenemedi.']);


        return $this->baseResponse->updateSuccessResponse($newUser);
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

    public function changeStatus($id)
    {
        $user = auth()->user();   

        if($user->role < $this->helper::USER_ROLE_ADMIN) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $changeStatus = $this->userRepository->changeStatus($id);

        if (!$changeStatus) 
            return $this->baseResponse->updateFailedResponse(null);

        return $this->baseResponse->updateSuccessResponse(null);
        
    }

}
