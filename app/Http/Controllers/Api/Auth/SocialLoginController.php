<?php

namespace App\Http\Controllers\Api\Auth;

use Google_Client;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class SocialLoginController extends Controller
{
    use ApiResponse;

    // googleLogin
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required|string',
            'provider' => 'required|in:google,facebook',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $email = $name = $providerId = $avatarUrl = null;

        try {
            if ($request->provider === 'google') {
                $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
                $payload = $client->verifyIdToken(trim($request->token));
                if (!$payload) {
                    return $this->error('Invalid Google token', 401);
                }
                $email      = $payload['email'];
                $name       = $request->name ?? $payload['name'] ?? 'User';
                $providerId = $payload['sub'];
                $avatarUrl  = $payload['picture'] ?? null;

            } elseif ($request->provider === 'facebook') {
                $socialUser = \Laravel\Socialite\Facades\Socialite::driver('facebook')->stateless()->userFromToken($request->token);
                $email      = $socialUser->getEmail();
                $name       = $socialUser->getName() ?? 'User';
                $providerId = $socialUser->getId();
                $avatarUrl  = $socialUser->getAvatar();
            }

            $avatarPath = null;
            if ($avatarUrl) {
                try {
                    $contents = file_get_contents($avatarUrl);
                    $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.jpg';
                    $directory = public_path('uploads/users');
                    
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    file_put_contents($directory . '/' . $filename, $contents);
                    $avatarPath = 'uploads/users/' . $filename;
                } catch (\Exception $e) {
                    $avatarPath = null;
                }
            }

            $updateData = [
                'name'              => $name,
                'provider'          => $request->provider,
                'provider_id'       => $providerId,
                'is_verified'       => 1,
                'email_verified_at' => now(),
            ];

            if ($avatarPath) {
                $updateData['image'] = $avatarPath;
            }

            $user = User::where('email', $email)->first();
            if (!$user) {
                $updateData['password'] = \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16));
            }

            $user = User::updateOrCreate(['email' => $email], $updateData);

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->setAttribute('token', $token);
            
            if($user->image) $user->image = asset($user->image);

            return $this->success($user, 'Login successful via ' . ucfirst($request->provider), 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // appleLogin
    public function appleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required|string',
            'provider' => 'required|in:apple',
            'email'    => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            if (env('APP_ENV') === 'local' && $request->token === 'test-apple-token') {
                $email = $request->email ?? 'test-apple-user@example.com';
                $providerId = 'apple_test_id_12345';
                $name = 'Apple Test User';
            } else {
                $socialUser = Socialite::driver('apple')->userFromToken($request->token);
                $email = $socialUser->getEmail();
                $providerId = $socialUser->getId();
                $name = $socialUser->getName() ?? explode('@', $email)[0];
            }

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'provider'    => 'apple',
                    'provider_id' => $providerId,
                    'is_verified' => 1,
                    'password'    => Hash::make(Str::random(16)),
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success(['user' => $user, 'token' => $token], 'Logged in with Apple Test Mode');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
