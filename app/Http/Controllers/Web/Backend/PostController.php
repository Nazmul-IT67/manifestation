<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
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
                                <a href="' . route('users.show', $data->id) . '" class="text-white btn btn-sm btn-info">
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

    // getSinglUser
    public function show($id) 
    {
        $user = User::with('details', 'subscriptions')->findOrFail($id);
        return view('backend.layouts.users.show', compact('user'));
    }

    // Edit User 
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.layouts.users.edit', compact('user'));
    }

    // Update User 
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::findOrFail($id);
        $imagePath = $user->image; 
        if ($request->hasFile('image')) {
            if ($user->image && File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/users/'), $filename);
            $imagePath = 'uploads/users/' . $filename;
        }

        $isActive = $request->has('is_active') ? (int)$request->is_active : $user->is_active;
        $isVerified = $request->has('is_verified') ? (int)$request->is_verified : $user->is_verified;

        $user->update([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'role'        => $request->role,
            'is_active'   => $isActive,
            'is_verified' => $isVerified,
            'image'       => $imagePath,
        ]);

        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'height'                   => $request->height,
                'weight'                   => $request->weight,
                'age'                      => $request->age,
                'experience_level'         => $request->experience_level,
                'primary_goal'             => $request->primary_goal,
                'location'                 => $request->location,
                'default_session_duration' => $request->default_session_duration,
                'preferred_sound_profile'  => $request->preferred_sound_profile,
                'daily_reminder_time'      => $request->daily_reminder_time,
                'stat_manifests'           => $request->stat_manifests,
                'stat_streak'              => $request->stat_streak,
                'stat_minutes'             => $request->stat_minutes,
                'bio'                      => $request->bio,
            ]
        );

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // updateStatus
    public function updateStatus($id)
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
