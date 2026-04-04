<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        if (! auth()->check()) {
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
        if (! $user) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'name'                     => 'nullable|string|max:255',
            'image'                    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone'                    => 'nullable|string|max:20',
            'height'                   => 'nullable|numeric',
            'weight'                   => 'nullable|numeric',
            'age'                      => 'nullable|integer',
            'experience_level'         => 'nullable|in:beginner,intermediate,expert',
            'primary_goal'             => 'nullable|string|max:255',
            'default_session_duration' => 'nullable|string|max:255',
            'preferred_sound_profile'  => 'nullable|string|max:255',
            'daily_reminder_time'      => 'nullable',
            'stat_manifests'           => 'nullable|integer',
            'stat_streak'              => 'nullable|integer',
            'stat_minutes'             => 'nullable|integer',
            'location'                 => 'nullable|string|max:255',
            'bio'                      => 'nullable|string',
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

                $file      = $request->file('image');
                $cleanName = str_replace(' ', '-', strtolower($user->name));
                $filename  = $cleanName . '_' . time() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads/users'), $filename);
                $user->image = 'uploads/users/' . $filename;
            }

            $user->save();

            $user->details()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'                    => $request->phone,
                    'height'                   => $request->height,
                    'weight'                   => $request->weight,
                    'age'                      => $request->age,
                    'experience_level'         => $request->experience_level,
                    'primary_goal'             => $request->primary_goal,
                    'default_session_duration' => $request->default_session_duration,
                    'preferred_sound_profile'  => $request->preferred_sound_profile,
                    'daily_reminder_time'      => $request->daily_reminder_time,
                    'stat_manifests'           => $request->stat_manifests,
                    'stat_streak'              => $request->stat_streak,
                    'stat_minutes'             => $request->stat_minutes,
                    'location'                 => $request->location,
                    'bio'                      => $request->bio,
                ]
            );

            $user->load('details');
            if ($user->image) {
                $user->image = asset($user->image);
            }

            return $this->success($user, 'Profile and details updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        if (! auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = $request->user();
        if (! Hash::check($request->old_password, $user->password)) {
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

    /**
     * Track Users Activity
     */
    private function trackUserActivity($userId)
    {
        UserActivity::updateOrCreate(
            [
                'user_id'    => $userId,
                'login_date' => Carbon::today()->toDateString(),
            ],
            [
                'is_active' => true,
            ]
        );
    }

// alamin_________________________________________________
    public function profile(Request $req)
    {
        $user = $req->user()->load('details');

        return $this->success([
             ...$user->toArray(),
            'is_subscribed' => $user->hasActiveSubscription(),
        ], 'user data fetched successfully');
    }

    public function updateCover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('', $validator->errors()->first(), 422);
        }

        $user = $request->user();

        if ($request->hasFile('image')) {

            if ($user->cover_image) {
                deleteFile($user->cover_image);
            }

            $path = uploadImage($request->file('image'), 'cover_image');
            $user->update(['cover_image' => $path]);
        }

        return $this->success($user, 'Cover image updated successfully');
    }
}
