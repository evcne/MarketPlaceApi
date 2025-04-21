<?php

namespace App\Modules\Company\Repositories;
use App\Modules\Company\Models\Company;
use App\Base\BaseRepository;
use App\Modules\User\Models\User;



class CompanyRepository extends BaseRepository
{
    public function getAllCompany($companyId)
    {
        $companies = Company::when($companyId, function ($query, $companyId) {
            return $query->where('id', $companyId);
        })->get();

        return $this->fetchAllAssociativeDTO($companies);
    }

    public function find($id)
    {
        return Company::findOrFail($id);
    }

    public function create(array $data)
    {
        $company = Company::create($data);
        return $this->fetchAllAssociativeDTO($company);
    }

    public function update($id, array $data)
    {
        $company = $this->find($id);
        $company->update($data);
        return $company;
    }

    public function updateCompanyIsApproved($id)
    {
        $company = $this->find($id);
        $company->update([
            'is_approved' => true,
        ]);
        
        return $this->fetchAllAssociativeDTO($company);
    }

    public function updateUserThenIsApproved($data)
    {

        $users = User::where('company_id', $data['company_id'])->get();
        foreach ($users as $user) {
            $user->update($data);

        }
        
        return $user;
    }

    public function changeStatus($id)
    {
        $company = $this->find($id);

        if ($company) {
            $company->status = false;
            $company->save();
        }
        return $this->fetchAllAssociativeDTO($company);
    }
}
