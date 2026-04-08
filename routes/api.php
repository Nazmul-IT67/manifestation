<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Post\LikeController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\AngelNumberController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\Post\CommentController;
use App\Http\Controllers\Api\Post\JournalController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Post\GeneralPostController;

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
    Route::get('user/profile', [UserController::class, 'profile']);
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

    // Quiz API
    Route::controller(QuizController::class)->group(function () {
        Route::get('/get-quiz', 'getQuiz');
        Route::get('/show-quiz', 'showQuiz');
        Route::post('/submit-quiz', 'storeQuiz');
    });

    // Session API
    Route::controller(SessionController::class)->prefix('session')->group(function () {
        Route::post('/book-now', 'storeBooking');
        Route::get('/session-types', 'getSessionTypes');
        Route::get('/available-slots', 'getAvailableSlots');
        Route::get('/booking-history', 'getBookingHistory');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'getHomeData');
    });





    //alamin__________________________________________

    //Task management section_________________________
    Route::apiResource('tasks', TaskController::class);
    Route::post('tasks/mark-as-complete/{task}', [TaskController::class, 'markAsComplete']);

    // Post management section________________________

    Route::apiResource('posts', GeneralPostController::class);
    Route::get('/new-feed', [GeneralPostController::class, 'NewFeed']);
    
    // discover post Section___________________________
    Route::get('/discover-posts', [GeneralPostController::class, 'UserPosts']);

    // comment management section____________________
    Route::controller(CommentController::class)->group(function () {
        Route::get('posts/{post}/comments', 'index');
        Route::post('posts/{post}/comments', 'store');
        Route::post('comments/{comment}', 'update');
        Route::delete('comments/{comment}', 'destroy');
        Route::get('/comment/{comment}', 'show');
    });

    //Like management section_________________________
    Route::post('posts/like/{post}', [LikeController::class, 'postLike']);
    Route::post('comments/like/{comment}', [LikeController::class, 'commentLike']);

    //journal post management section_________________

    Route::controller(JournalController::class)->group(function () {
        Route::get('journals', 'index');
        Route::post('journals', 'store');
        Route::get('journals/{journal}', 'show');
        Route::post('journals/{journal}', 'update');
        Route::delete('journals/{journal}', 'destroy');
    });

    //categories management section____________________

    Route::get('/categories/{category}', [CategoriesController::class, 'show']);

    // Angel number management section ________________
    Route::get('angel-numbers', [AngelNumberController::class, 'index']);
    Route::get('angel-numbers/my', [AngelNumberController::class, 'myAngelNumber']);
    Route::post('angel-numbers/select/{angelNumber}', [AngelNumberController::class, 'select']);
});
