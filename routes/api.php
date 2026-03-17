<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Post\CommentController;
use App\Http\Controllers\Api\Post\GeneralPostController;
use App\Http\Controllers\Api\Post\JournalController;
use App\Http\Controllers\Api\Post\LikeController;
use App\Http\Controllers\Api\TaskController;

// RegisterUser API
Route::controller(RegisterController::class)->prefix('users')->group(function () {
    Route::post('/register', 'Register');
    Route::post('/otp-verify', 'verifyOTP');
    Route::post('/resend-otp', 'resendOTP');
});

// LoginUser API
Route::controller(LoginController::class)->prefix('users')->group(function () {
    Route::post('/login', 'Login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

// SocialLogin API
Route::controller(SocialLoginController::class)->prefix('social-login')->group(function () {
    Route::post('google', 'googleLogin');
    Route::post('apple', 'appleLogin');
});

// Users API
Route::middleware(['auth:sanctum', 'track.activity'])->group(function () {
    Route::get('users/index', [UserController::class, 'Index']);
    Route::post('users/logout', [UserController::class, 'Logout']);
    Route::post('/users/update', [UserController::class, 'Update']);
    Route::post('users/change-password', [UserController::class, 'changePassword']);
    Route::get('user/profile',[UserController::class,'profile']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Content API
    Route::controller(ContentController::class)->prefix('contents')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
        Route::delete('/delete', 'destroy');
    });

    // Category API
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
        Route::delete('/delete', 'destroy');
    });

    // SubscriptionPlan API
    Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
        Route::get('/index', 'index');
        Route::get('/show', 'show');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
    });

    // Subscription API
    Route::controller(UserSubscriptionController::class)->prefix('subscription')->group(function () {
        Route::get('/current', 'currentSubscription');
        Route::post('/purchase', 'purchaseSubscription');
    });





    //alamin__________________________________________


    //Task management section_________________________
    Route::apiResource('tasks', TaskController::class);
    Route::post('tasks/mark-as-complete/{task}', [TaskController::class, 'markAsComplete']);


    // Post management section________________________

    Route::apiResource('posts', GeneralPostController::class);
    Route::get('/new-feed', [GeneralPostController::class, 'NewFeed']);

    // comment management section____________________
    Route::controller(CommentController::class)->group(function () {
        Route::get('posts/{post}/comments', 'index');
        Route::post('posts/{post}/comments',  'store');
        Route::post('comments/{comment}',  'update');
        Route::delete('comments/{comment}',  'destroy');
        Route::get('/comment/{comment}', 'show');
    });

    //Like management section_________________________
    Route::post('posts/like/{post}',    [LikeController::class, 'postLike']);
    Route::post('comments/like/{comment}', [LikeController::class, 'commentLike']);


    //journal post management section_________________

    Route::controller(JournalController::class)->group(function () {
        Route::get('journals', 'index');
        Route::post('journals', 'store');
        Route::get('journals/{journal}', 'show');
        Route::post('journals/{journal}', 'update');
        Route::delete('journals/{journal}', 'destroy');
    });
});
