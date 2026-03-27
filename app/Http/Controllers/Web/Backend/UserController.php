<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // getAllUsers
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = User::with('currentSubscription')->latest();
            if ($request->has('search.value') && !empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%")
                        ->orWhere('email', 'LIKE', "%$searchTerm%")
                        ->orWhere('phone', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_type', function ($user) {
                    if ($user->currentSubscription) {
                        return '<span class="badge bg-success">Premium</span>';
                    }
                    return '<span class="badge bg-secondary">Free</span>';
                })

                ->addColumn('joined', function ($data) {
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
                                <a href="' . route('users.edit', $data->id) . '" class="text-white btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="' . route('users.edit', $data->id) . '" class="text-white btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'action', 'user_type'])
                ->make(true);
        }

        return view('backend.layouts.users.index');
    }

    // updateStatus
    public function updateAccountStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'Activated' : 'Deactivated';

        return response()->json([
            'success' => true,
            'message' => 'User account has been ' . $statusText
        ]);
    }

    // destroy
    public function destroy(User $user)
    {
        try {
            if ($user->subscriptions()->exists()) {
                $user->subscriptions()->delete();
            }

            $user->delete(); 
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}