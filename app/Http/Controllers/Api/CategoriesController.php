<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponse;

class CategoriesController extends Controller
{
    use ApiResponse;

    public function show(Category $category)
    {
        return $this->success($category, 'Categories information fetched successfully');
    }
}
