<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reset', function () {
    Artisan::call('migrate:fresh', [
        '--seed' => true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Database has been refreshed and seeded successfully!'
    ]);
});

require __DIR__.'/auth.php';