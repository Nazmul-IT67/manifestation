<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $page_title = 'Profile Settings';
        $userDetails = User::where('id', Auth::id())->first();
        return view('backend.layouts.settings.profile', ['userDetails' => $userDetails], compact('page_title'));
    }

    /**
     * Update the user's profile information.
     *
     */
    // public function UpdateProfile(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name'         => 'required|string|max:255',
    //         'email'        => 'required|email|unique:users,email,' . Auth::id(),
    //         'old_password' => 'nullable|string',
    //         'new_password' => 'nullable|string|min:6|confirmed',
    //         'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }
        
    //     $user = Auth::user();

    //     if($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = Str::slug($request->name) . '_' . time() . '.' . $extension;

    //         if($user->image && file_exists(public_path($user->image))) {
    //             unlink(public_path($user->image));
    //         }

    //         $file->move(public_path('uploads/users'), $filename);
    //         $user->image = 'uploads/users/' . $filename;
    //     }

    //     $user->name  = $request->name;
    //     $user->email = $request->email;

    //     if($request->filled('new_password')){
    //         if(!Hash::check($request->old_password, $user->password)){
    //             return redirect()->back()->with('error', 'Current password is incorrect');
    //         }
    //         $user->password = Hash::make($request->new_password);
    //     }

    //     $user->save();
    //     return back()->with('success', 'Updated successfully');
    // }

    public function UpdateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . Auth::id(),
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'new_password.confirmed' => 'The password confirmation does not match the new password.',
            'new_password.min'       => 'Your new password should be at least 6 characters long.',
            'email.unique'           => 'This email is already associated with another account.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user = Auth::user();

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($request->name) . '_' . time() . '.' . $extension;

            if($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $file->move(public_path('uploads/users'), $filename);
            $user->image = 'uploads/users/' . $filename;
        }

        $user->name  = $request->name;
        $user->email = $request->email;

        if($request->filled('new_password')){
            if(!Hash::check($request->old_password, $user->password)){
                return redirect()->back()->withErrors(['old_password' => 'Your current password does not match our records.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return back()->with('success', 'Your profile has been updated successfully!');
    }
}

