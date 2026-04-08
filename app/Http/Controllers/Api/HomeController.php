<?php

namespace App\Http\Controllers\Api;

use App\Models\Content;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use ApiResponse;
    
    public function getHomeData()
    {
        $user = auth()->user();
        $userTimezone = \DB::table('user_details')
            ->where('user_id', $user->id)
            ->value('timezone') ?? 'Asia/Dhaka';

        $now = now($userTimezone);
        $hour = $now->hour;

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 17) {
            $greeting = 'Good Afternoon';
        } elseif ($hour < 21) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        $categories = Category::where('is_active', true)->get();
        $quickStart = Content::with('category')
            ->where('is_active', true) 
            ->latest()
            ->take(10)
            ->get()
            ->map(function($content) {
                return [
                    'id'           => $content->id,
                    'title'        => $content->title,
                    'sub_title'    => $content->sub_title,
                    'thumbnail'    => asset($content->thumbnail),
                    'type'         => $content->content_type,
                    'duration'     => $content->duration,
                    'is_premium'   => (bool) $content->is_premium,
                    'category_name'=> $content->category->title ?? '',
                ];
            });

        return $this->success([
            'user' => [
                'name'  => $user->name,
                'image' => $user->avatar ? asset($user->avatar) : null,
                'greeting' => $greeting,
                'current_time_in_timezone' => now($userTimezone)->format('h:i A'),
            ],
            'categories' => $categories,
            'content'    => $quickStart,
        ], 'Home data retrieved successfully!');
    }
}
