<?php

namespace App\Modules\Category\Repositories;
use App\Modules\Category\Models\Category;
use App\Base\BaseRepository;



class CategoryRepository extends BaseRepository
{
    public function getAllCategory($categoryStatus)
    {

        $categoryList = Category::all();

        if($categoryStatus) {
            $categoryList = Category::where('status', 'true')->get();

        }

        return $this->fetchAllAssociativeDTO($categoryList);
    }

    public function find($id)
    {
        return $this->fetchAllAssociativeDTO(Category::findOrFail($id));
    }

    public function create(array $data)
    {
        $category = Category::create($data);
        return $this->fetchAllAssociativeDTO($category);
    }

    public function update($id, array $data)
    {
        $category = Category::find($id);
        $category->update($data);
        return $category;
    }

    public function changeStatus($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->status = false;
            $category->save();
        }
        return $this->fetchAllAssociativeDTO($category);
    }
}
