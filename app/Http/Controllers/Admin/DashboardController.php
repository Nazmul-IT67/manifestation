<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Journal;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPosts = Journal::count();
        $totalSessions = Booking::count();
        $completedSessions = Booking::where('status', 'completed')->count();

        return view('backend.layouts.index', compact('totalUsers', 'totalPosts', 'totalSessions', 'completedSessions'));
    }
}
