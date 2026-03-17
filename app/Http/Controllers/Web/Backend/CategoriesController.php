<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = Category::latest();

            return DataTables::of($query)
                ->addColumn('serial', function ($row) {
                    static $i = 0;
                    $i++;
                    return 'C-' . $i;
                })
                ->addColumn('title', fn($c) => $c->title ?? 'N/A')
                ->addColumn('image', function ($c) {
                    if ($c->image) {
                        return '<img src="' . asset($c->image) . '" width="50"/>';
                    }
                    return 'N/A';
                })
                ->addColumn('icon', fn($c) => $c->icon ?? 'N/A')
                ->addColumn('status', function ($c) {
                    $next = $c->is_active ? 0 : 1;
                    $checked = $c->is_active ? 'checked' : '';
                    return '
                    <a href="#" class="change_status" data-id="' . $c->id . '" data-enabled="' . $next . '">
                        <label class="switch">
                            <input type="checkbox" ' . $checked . '>
                            <span class="slider round"></span>
                        </label>
                    </a>';
                })
                ->addColumn('action', function ($c) {
                    return '
                        <a href="' . route('categories.edit', $c->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger deletebtn" data-id="' . $c->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['image', 'status', 'action'])
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
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'categories');
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
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            deleteFile($category->image);
            $data['image'] = uploadFile($request->file('image'), 'categories');
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    // Delete category
    public function destroy(Category $category)
    {
        deleteFile($category->image);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
