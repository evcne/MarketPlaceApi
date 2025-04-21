<?php

namespace App\Modules\Company\Services;

use App\Modules\Company\Repositories\CompanyRepository;
use App\Helpers\Helper;
use App\Base\BaseResponse;
use App\Base\BaseService;
use Illuminate\Support\Facades\Http;

class CompanyService extends BaseService
{
    protected CompanyRepository $repository;

    public function __construct(protected CompanyRepository $companyRepository, protected Helper $helper, protected BaseResponse $baseResponse)
    {
        parent::__construct($helper);

    }

    public function getAll()
    {

        $user = auth()->user();   

        if (!$user) {
            return $this->baseResponse->authFailedResponse(null);
        }

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $userCompanyId = null;
        if($user->role == $this->helper::USER_ROLE_VENDOR) {
            $userCompanyId = $user->company_id;
        }


        $companyList = $this->companyRepository->getAllCompany($userCompanyId);


        if(!$this->helper->checkIfExist($companyList))
            return $this->baseResponse->readFailedResponse(null);


        return $this->baseResponse->readSuccessResponse($companyList);
    }

    public function getById($id)
    {
        $user = auth()->user();   

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        return $this->companyRepository->find($id);
    }

    public function create(array $data)
    {

        if(auth()->user()->company_id)
            return $this->baseResponse->createFailedResponse(['message' => 'Daha önce eklediğiniz bir şirketiniz bulunmaktadır, Lütfen onay sürecini bekleyiniz.']);


        $user = auth()->user();    
        $data['create_user_id'] = $user->id;

        $newCompany = $this->companyRepository->create($data);


        if(!$this->helper->checkIfExist($newCompany))
            return $this->baseResponse->createFailedResponse(['message' => 'Şirket oluşturulamadı.']);

        $user = auth()->user();
        $user->company_id = $newCompany['data']['id'];
        $user->save();

        return $this->baseResponse->createSuccessResponse($newCompany);
    }

    public function companyApprovedByAdmin($id)
    {
        $user = auth()->user();   

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        if(auth()->user()->role != $this->helper::USER_ROLE_ADMIN)
            return $this->baseResponse->createFailedResponse(['message' => 'Admin yetkisi gerekli']);


        $companyIsApproved = $this->companyRepository->updateCompanyIsApproved($id);   
        

        if ($companyIsApproved) {
            $companyId = $companyIsApproved['data']['id'];


            //Microservis için gerekli silme
            /*$user = auth()->user(); 
            $url = env('USER_SERVICE_URL') . "api/users/updateUserRole";

            $updateUser = Http::put($url, [
                'company_id' => $companyId,
                'update_user_id' => $user->id,
                'is_vendor' => true,
                'role' => $this->helper::USER_ROLE_VENDOR,
            ]);*/


            $user = auth()->user();    
            $userData['update_user_id'] = $user->id;
            $userData['company_id'] = $companyId;
            $userData['is_vendor'] = true;
            $userData['role'] = $this->helper::USER_ROLE_VENDOR;
            $userData['update_date'] = now();

            
            $updateUser = $this->companyRepository->updateUserThenIsApproved($userData);  
            
            if (!$updateUser) 
                return $this->baseResponse->updateFailedResponse(null);


        } else {
            return $this->baseResponse->updateFailedResponse(null);
        }

        return $this->baseResponse->updateSuccessResponse(null);
    }

    public function update($id, array $data)
    {
        $user = auth()->user();   

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        return $this->companyRepository->update($id, $data);
    }

    public function changeStatus($id)
    {
        $user = auth()->user();   

        if($user->role == $this->helper::USER_ROLE_CUSTOMER) {
            return $this->baseResponse->authFailedResponse(null);
        }

        $changeStatus = $this->companyRepository->changeStatus($id);

        if (!$changeStatus) 
            return $this->baseResponse->updateFailedResponse(null);

        return $this->baseResponse->updateSuccessResponse(null);
        
    }
}
