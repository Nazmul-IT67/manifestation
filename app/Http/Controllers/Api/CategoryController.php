<?php
namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponse;

    // Index 
    public function index()
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $categories = Category::all();
        return $this->success($categories, 'Categories retrieved successfully.');
    }

    // Show 
    public function show(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $category = Category::find($request->id);
        if (!$category) {
            return $this->error(null, 'Category not found.', 404);
        }

        return $this->success($category, 'Category retrieved successfully.', 200);
    }

    // Store 
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255|unique:categories,title',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'icon'        => 'nullable|file|mimes:png,svg,ico,jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $data = $request->only(['title', 'description']);

            if ($request->hasFile('image')) {
                $imageName = time().'_img.'.$request->image->extension();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = 'uploads/categories/'.$imageName;
            }

            if ($request->hasFile('icon')) {
                $iconName = time().'_icon.'.$request->icon->extension();
                $request->icon->move(public_path('uploads/icons'), $iconName);
                $data['icon'] = 'uploads/icons/'.$iconName;
            }

            $category = Category::create($data);
            return $this->success($category, 'Category created successfully.', 201);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // Update 
    public function update(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $category = Category::find($request->id);
        if (!$category) {
            return $this->error('Category not found.', 404);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255|unique:categories,title,' . $category->id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'icon'        => 'nullable|file|mimes:png,svg,ico,jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $data = $request->only(['title', 'description']);

            if ($request->hasFile('image')) {
                if ($category->getRawOriginal('image') && File::exists(public_path($category->getRawOriginal('image')))) {
                    File::delete(public_path($category->getRawOriginal('image')));
                }
                $imageName = time().'_img.'.$request->image->extension();
                $request->image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = 'uploads/categories/'.$imageName;
            }

            if ($request->hasFile('icon')) {
                if ($category->getRawOriginal('icon') && File::exists(public_path($category->getRawOriginal('icon')))) {
                    File::delete(public_path($category->getRawOriginal('icon')));
                }
                $iconName = time().'_icon.'.$request->icon->extension();
                $request->icon->move(public_path('uploads/icons'), $iconName);
                $data['icon'] = 'uploads/icons/'.$iconName;
            }

            $category->update($data);
            return $this->success($category, 'Category updated successfully.', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // Delete
    public function destroy(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $category = Category::find($request->id);
        if (!$category) {
            return $this->error('Category not found.', 404);
        }

        try {
            if ($category->getRawOriginal('image') && File::exists(public_path($category->getRawOriginal('image')))) {
                File::delete(public_path($category->getRawOriginal('image')));
            }
            if ($category->getRawOriginal('icon') && File::exists(public_path($category->getRawOriginal('icon')))) {
                File::delete(public_path($category->getRawOriginal('icon')));
            }

            $category->delete();
            return $this->success(null, 'Category deleted successfully.', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}