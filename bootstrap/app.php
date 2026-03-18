<?php

use App\Console\Commands\ExpireStories;
use App\Http\Middleware\Admin;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(function () {
                    require base_path('routes/backend.php');
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'admin'          => Admin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Unauthenticated
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated',
                    'data'    => [],
                    'code'    => 401,
                ], 401);
            }
            return redirect()->route('login');
        });

        // Model Not Found
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => class_basename($e->getModel()) . ' not found',
                    'data'    => [],
                    'code'    => 404,
                ], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // Unauthorized
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthorized',
                    'data'    => [],
                    'code'    => 403,
                ], 403);
            }
            return response()->view('errors.403', [], 403);
        });

        // Validation
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'data'    => $e->errors(),
                    'code'    => 422,
                ], 422);
            }
        });

        // Route Not Found
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => '😢 Item not found',
                    'data'    => [],
                    'code'    => 404,
                ], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // Method Not Allowed
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $allow = $e->getHeaders()['Allow'] ?? '';
                return response()->json([
                    'status'  => false,
                    'message' => '😒 Method not allowed. Allowed: ' . $allow,
                    'data'    => [],
                    'code'    => 405,
                ], 405);
            }
        });

        // Server Error
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => app()->isProduction() ? 'Server error' : $e->getMessage(),
                    'data'    => [],
                    'code'    => 500,
                ], 500);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(ExpireStories::class)->everyTwoMinutes();
    })
    ->create();