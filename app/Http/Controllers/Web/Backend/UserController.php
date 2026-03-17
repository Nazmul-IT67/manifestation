<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::latest();
            dd($query);

            return DataTables::of($query)
                ->addColumn('serial', function ($row) {
                    static $i = 1000;
                    $i++;
                    return 'U-' . $i;
                })
                ->addColumn('name', fn($u) => $u->name ?? 'N/A')
                ->addColumn('email', fn($u) => $u->email ?? 'N/A')
                ->addColumn('role', fn($u) => $u->email ?? 'N/A')
                ->addColumn('is_active', function ($u) {
                    $next = $u->status ? 0 : 1;
                    $checked = $u->status ? 'checked' : '';
                    $title = $u->status ? 'Deactivate User' : 'Activate User';
                    $desc = $u->status ? 'Are you sure you want to deactivate?' : 'Are you sure you want to activate?';
                    return '
    <a href="#" class="change_status" 
        data-id="' . $u->id . '" 
        data-enabled="' . $next . '"
        data-title="' . $title . '"
        data-description="' . $desc . '"
        data-bs-toggle="modal" 
        data-bs-target="#statusModal">
        <label class="switch">
            <input type="checkbox" ' . $checked . '>
            <span class="slider round"></span>
        </label>
    </a>';
                })
                ->addColumn('action', function ($u) {
                    return '<button class="deletebtn" data-id="' . $u->id . '">Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }



        return view('backend.layouts.users.index');
    }

    public function updateAccountStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|boolean']); // ✅ status রাখো
        $user->update(['status' => (bool)$request->status]);
        return response()->json(['status' => 'success']);
    }
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => 'success']);
    }
}
