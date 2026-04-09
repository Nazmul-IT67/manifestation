<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ContentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Content::with('category')->latest();
            if ($request->has('search.value') && !empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where(function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%$searchTerm%");
                });
            }
            return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('category', function ($row) {
                return $row->category->title ?? 'N/A';
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

            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y') : 'N/A';
            })

            ->addColumn('action', function ($row) {
                return '<div class="d-flex gap-1">
                            <a href="' . route('contents.edit', $row->id) . '" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                            <button onclick="showDeleteConfirm(' . $row->id . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['status', 'is_premium', 'action'])
            ->make(true);
        }

        return view('backend.layouts.content.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::latest()->get();
        return view('backend.layouts.content.create', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', //
            'content_url'  => 'nullable|string',
            'content_type' => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $directory = 'uploads/contents/';
                
                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0777, true);
                }
                
                $file->move(public_path($directory), $filename);
                $data['thumbnail'] = $directory . $filename;
            }

            $data['is_premium'] = $request->has('is_premium') ? $request->is_premium : 0;

            Content::create($data);

            return redirect()->route('contents.index')->with('success', 'Content created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = Content::findOrFail($id);
        $categories = Category::latest()->get();
        return view('backend.layouts.content.edit', compact('content', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content_url'  => 'nullable|string',
            'content_type' => 'nullable|string',
        ]);

        try {
            $content = Content::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                if ($content->thumbnail && file_exists(public_path($content->thumbnail))) {
                    unlink(public_path($content->thumbnail));
                }

                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $directory = 'uploads/contents/';
                
                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0777, true);
                }
                
                $file->move(public_path($directory), $filename);
                $data['thumbnail'] = $directory . $filename;
            } else {
                $data['thumbnail'] = $content->thumbnail;
            }

            $data['is_premium'] = $request->has('is_premium') ? $request->is_premium : 0;

            $content->update($data);

            return redirect()->route('contents.index')->with('success', 'Content updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $content = Content::findOrFail($id);
            if ($content->thumbnail && file_exists(public_path($content->thumbnail))) {
                unlink(public_path($content->thumbnail));
            }

            $content->delete();

            return response()->json([
                'success' => true,
                'message' => 'Content deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    // updateStatus
    public function updateStatus($id)
    {
        $data = Content::findOrFail($id);
        $data->is_active = !$data->is_active;
        $data->save();

        $statusText = $data->is_active ? 'Activated' : 'Deactivated';

        return response()->json([
            'success' => true,
            'message' => 'Status change successfully' . $statusText
        ]);
    }

}
