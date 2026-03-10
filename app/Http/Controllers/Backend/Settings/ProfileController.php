<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $page_title = 'Profile Setting';
        return view('backend.layouts.settings.profile', compact('page_title', 'user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . Auth::id(),
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:6|confirmed',
        ]);
        
        $user = Auth::user();

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Delete old image
            if($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Move new image
            $file->move(public_path('uploads/users'), $filename);
            $user->image = 'uploads/users/' . $filename;
        }

        $user->name  = $request->name;
        $user->email = $request->email;

        if($request->filled('new_password')){
            if($request->current_password && !Hash::check($request->current_password, $user->password)){
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Updated successfully');
    }

}
