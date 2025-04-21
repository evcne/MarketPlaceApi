<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Company\Services\CompanyService;
use App\Base\BaseResponse;

class CompanyController extends Controller
{

    public function __construct(protected CompanyService $companyService)
    {
    }

    public function index()
    {
        $companyList = $this->companyService->getAll();
        return $companyList;
    }

    public function show($id)
    {
        return response()->json($this->companyService->getById($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $newCompany = $this->companyService->create($validated);
        return $newCompany;

    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'email' => 'sometimes|email',
        ]);

        return response()->json($this->companyService->update($id, $validated));
    }

    public function companyApprovedByAdmin($id)
    {
        $companyIsApprovedChange = $this->companyService->companyApprovedByAdmin($id);
        return $companyIsApprovedChange;
    }

    public function changeStatus($id)
    {
        $companyChangeStatus = $this->companyService->changeStatus($id);
        return $companyChangeStatus;
    }
}
