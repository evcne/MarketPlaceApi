<?php

namespace App\Modules\Category\Services;

use App\Modules\Category\Repositories\CategoryRepository;
use App\Helpers\Helper;
use App\Base\BaseResponse;
use App\Base\BaseService;



class CategoryService extends BaseService
{


    public function __construct(protected CategoryRepository $categoryRepository, protected Helper $helper, protected BaseResponse $baseResponse)
    {
        parent::__construct($helper);
    }

    public function getAllCategory()
    {

        $user = $this->getUserWithAutoRefresh();
        if (!$user) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $categoryStatus = true;
        if($user->role == $this->helper::USER_ROLE_ADMIN || 
        $user->role == $this->helper::USER_ROLE_SUPER_ADMIN) {
            $categoryStatus = false;
        }

        $categoryList = $this->categoryRepository->getAllCategory($categoryStatus);

        if(!$this->helper->checkIfExist($categoryList))
            return $this->baseResponse->readFailedResponse(null);


        return $this->baseResponse->readSuccessResponse($categoryList);
    }

    public function getById($id)
    {
        $user = auth()->user();   

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        return $this->categoryRepository->find($id);
    }

    public function createCategory($data)
    {
        if($user->role < $this->helper::USER_ROLE_ADMIN) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $newCategory = $this->categoryRepository->create($data);

        if(!$this->helper->checkIfExist($newCategory))
            return $this->baseResponse->createFailedResponse(['message' => 'Kategori oluşturulamadı.']);


        return $this->baseResponse->createSuccessResponse($newCategory);
    }

    public function updateCategory($data)
    {
        if($user->role < $this->helper::USER_ROLE_ADMIN) {
            return $this->baseResponse->authFailedResponse(null);
        }
        
        $updateCategory = $this->categoryRepository->updateCategory($data);

        if(!$this->helper->checkIfExist($updateCategory))
            return $this->baseResponse->updateFailedResponse(['message' => 'Kategori güncellenemedi.']);


        return $this->baseResponse->updateSuccessResponse($updateCategory);
    }

    public function changeStatus($id)
    {
        $user = auth()->user();   

        if($user->role < $this->helper::USER_ROLE_ADMIN) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $changeStatus = $this->categoryRepository->changeStatus($id);

        if (!$changeStatus) 
            return $this->baseResponse->updateFailedResponse(null);

        return $this->baseResponse->updateSuccessResponse(null);
        
    }

}
