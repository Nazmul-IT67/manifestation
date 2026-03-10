<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use ApiResponse;

    /**
     * User Register
     */
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'nullable|string|unique:users,phone',
            'password'       => 'required|string|min:8|confirmed',
            'agree_to_terms' => 'required|accepted', 
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        try {
            $user = new User();
            $user->name           = $request->input('name');
            $user->email          = $request->input('email');
            $user->phone          = $request->input('phone');
            $user->password       = Hash::make($request->input('password'));
            $user->agree_to_terms = $request->input('agree_to_terms'); 
            $user->save();

            $otp = rand(100000, 999999);

            EmailOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'verification_code' => $otp,
                    'expires_at'        => now()->addMinutes(5)
                ]
            );

            Mail::to($user->email)->send(new OtpMail($user, $otp));

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->setAttribute('token', $token);

            return $this->success($user, 'Registration successful. Please verify the OTP sent to your email.', 201);
            
        }  catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Verify the OTP sent to the user
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $user = User::where('email', $request->input('email'))->first();

            $verification = EmailOtp::where('user_id', $user->id)
                ->where('verification_code', $request->input('otp'))
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($verification) {
                $user->email_verified_at = Carbon::now();
                $user->is_verified = 1;
                $user->save();

                $verification->delete();
                $token = $user->createToken('auth_token')->plainTextToken;
                $user->setAttribute('token', $token);

                return $this->success($user, 'OTP verified successfully.', 200);
            } else {
                return $this->error('Invalid or expired OTP.', 400);
            }
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    // Resend OTP
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $otp = rand(100000, 999999);

            EmailOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'verification_code' => $otp,
                    'expires_at'        => now()->addMinutes(5)
                ]
            );

            Mail::to($user->email)->send(new OtpMail($user, $otp));

            return $this->success([], 'A new OTP has been sent to your email.', 200);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
