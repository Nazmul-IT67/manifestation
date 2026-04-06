<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    // getAllPost
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::with('user')->latest();

            if ($request->has('search.value') && !empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where(function($query) use ($searchTerm) {
                    $query->where('content', 'LIKE', "%$searchTerm%")
                        ->orWhere('post_type', 'LIKE', "%$searchTerm%")
                        ->orWhereHas('user', function($q) use ($searchTerm) {
                            $q->where('name', 'LIKE', "%$searchTerm%");
                        });
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    $class = $row->post_type == 'journal' ? 'bg-info' : ($row->post_type == 'story' ? 'bg-warning' : 'bg-primary');
                    return '<span class="badge ' . $class . '">' . ucfirst($row->post_type) . '</span>';
                })
                ->addColumn('author', function ($row) {
                    return $row->user ? $row->user->name : '<span class="text-danger">Unknown</span>';
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? "checked" : "";
                    return '
                        <div class="form-check form-switch d-flex">
                            <input onclick="showStatusChangeAlert(' . $row->id . ')"
                                type="checkbox"
                                class="form-check-input status-toggle"
                                id="switch' . $row->id . '"
                                ' . $checked . '>
                            <label class="form-check-label ms-2" for="switch' . $row->id . '"></label>
                        </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-1" role="group">
                                <a href="' . route('journal.edit', $row->id) . '" class="text-white btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="showDeleteConfirm(' . $row->id . ')" class="text-white btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'action', 'type', 'author'])
                ->make(true);
        }

        return view('backend.layouts.journal.post.index');
    }

    // getSinglUser
    public function show($id) 
    {
        //
    }

    // Edit User 
    public function edit($id)
    {
        //
    }

    // Update User 
    public function update(Request $request, $id)
    {
        //
    }

    // Delete
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete post. Please try again.'
            ], 500);
        }
    }

    // updateStatus
    public function updateStatus($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->is_active = !$post->is_active;
            $post->save();

            $statusText = $post->is_active ? 'Activated' : 'Deactivated';

            return response()->json([
                'success' => true,
                'message' => 'Post has been ' . $statusText . ' successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating status!'
            ], 500);
        }
    }
}
