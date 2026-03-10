<?php

namespace App\Http\Controllers\Api\Auth;

use Google_Client;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'provider' => 'required|in:google,facebook',
        ]);

        $email = $name = $providerId = $avatarUrl = null;

        if ($request->provider === 'google') {
            $client = new Google_Client(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken(trim($request->token));
            if (!$payload) {
                return $this->error('Invalid Google token', 401);
            }
            $email      = $payload['email'];
            $name       = $request->name ?? $payload['name'] ?? ($payload['given_name'] ?? 'User');
            $providerId = $payload['sub'];
            $avatarUrl  = $payload['avatar'] ?? null;

        } elseif ($request->provider === 'facebook') {
            $socialUser = Socialite::driver('facebook')->stateless()->userFromToken($request->token);
            $email      = $socialUser->getEmail();
            $name       = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';
            $providerId = $socialUser->getId();
            $avatarUrl  = $socialUser->getAvatar();
        }

        $avatarUrl = $request->avatar ?? $payload['avatar'] ?? null;
        if ($avatarUrl) {
            $contents = file_get_contents($avatarUrl);
            $filename = time() . '_' . Str::random(10) . '.jpg';
            $path = public_path('uploads/' . $filename);
            file_put_contents($path, $contents);
            $avatarPath = 'uploads/' . $filename;
        }else{
            $avatarPath = null;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'avatar'   => $avatarPath ?? $user->avatar ?? null,
                $request->provider.'_id' => $providerId,
                'password' => bcrypt(Str::random(16)),
                'provider' => $request->provider,
            ]
        );

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->success([
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'avatar'  => $user->avatar ? : null,
            'token'   => $token,
        ]);
    }
}
