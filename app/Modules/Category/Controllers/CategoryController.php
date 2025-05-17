<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Category\Services\CategoryService;
use App\Base\BaseResponse;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected BaseResponse $baseResponse)
    {}     
    

    public function index()
    {
        $categories = $this->categoryService->getAllCategory();
        return $categories;
    }

    public function show($id)
    {
        $category = $this->categoryService->getById($id);
        return $category;
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'comment', 'status');
        $categories = $this->categoryService->createCategory($data);
        return $categories;
    }

    public function update(Request $request)
    {
        
        $data = $request->only('name', 'comment');
        $update = $this->categoryService->updateCategory($data);
        return $update;

    }

    public function changeStatus($id)
    {
        $categoryChangeStatus = $this->categoryService->changeStatus($id);
        return $categoryChangeStatus;
    }
}
