<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Get All Users
     */
    public function Index()
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        try {
            $users = User::latest()->paginate(10);
            
            return $this->success($users, 'All users with all details retrieved successfully.', 200);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Update User
     */
    public function Update(Request $request)
    {
        $user = auth()->user(); 
        if (!$user) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            if ($request->hasFile('image')) {
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                $file = $request->file('image');
                $cleanUserName = str_replace(' ', '-', strtolower($user->name));
                $filename = $cleanUserName . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                $file->move(public_path('uploads/users'), $filename);
                $user->image = 'uploads/users/' . $filename;
            }

            $user->save();

            $user->image = $user->image ? asset($user->image) : null;
            return $this->success($user, 'Profile updated successfully.', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->error('Old password does not match.', 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success([], 'Password changed successfully.', 200);
    }

    /**
     * Logout Method
     */
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([], 'Logged out successfully.', 200);
    }
}