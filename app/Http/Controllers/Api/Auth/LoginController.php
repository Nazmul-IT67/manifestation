<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;

    /**
     * Login Method
     */
    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('Invalid email or password.', 401);
            }

            if ($user->is_verified == 0) {
                return $this->error('Your account is not verified. Please verify your email first.', 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->setAttribute('token', $token);

            return $this->success($user, 'Login successful.', 200);

        } catch (\Exception $e) {
            return $this->error("Login failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Forgot Password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);

        EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            ['verification_code' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::to($user->email)->send(new OtpMail($user, $otp));

        return $this->success([], 'Password reset OTP sent to your email.', 200);
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'otp'      => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = User::where('email', $request->email)->first();

        $verification = EmailOtp::where('user_id', $user->id)
            ->where('verification_code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return $this->error('Invalid or expired OTP.', 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $verification->delete();

        return $this->success([], 'Password reset successfully. You can now login.', 200);
    }
}