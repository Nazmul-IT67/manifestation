<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::latest();
            if ($request->has('search.value') && !empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where(function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%$searchTerm%")
                        ->orWhere('image', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('d M, Y');
                })
                ->addColumn('status', function ($user) {
                    $checked = $user->is_active ? "checked" : "";
                    return '
                        <div class="form-check form-switch d-flex">
                            <input onclick="showStatusChangeAlert(' . $user->id . ')"
                                type="checkbox"
                                class="form-check-input status-toggle"
                                id="switch' . $user->id . '"
                                ' . $checked . '>
                            <label class="form-check-label ms-2" for="switch' . $user->id . '"></label>
                        </div>';
                })

                ->addColumn('action', function ($data) {
                    return '<div class="d-flex gap-1" role="group">
                                <a href="' . route('categories.edit', $data->id) . '" class="text-white btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['created_at', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.categories.index');
    }

    // Show create form
    public function create()
    {
        return view('backend.layouts.categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'icon'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'uploads/categories');
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    // Show single category
    public function show(Category $category)
    {
        return view('backend.layouts.categories.show', compact('category'));
    }

    // Show edit form
    public function edit(Category $category)
    {
        return view('backend.layouts.categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'icon'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            deleteFile($category->image);
            $data['image'] = uploadFile($request->file('image'), 'uploads/categories');
        }

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    // updateStatus
    public function updateStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $statusText = $category->is_active ? 'Activated' : 'Deactivated';

        return response()->json([
            'success' => true,
            'message' => 'User account has been ' . $statusText
        ]);
    }

    // Delete category
    public function destroy(Category $category)
    {
        try {
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}
