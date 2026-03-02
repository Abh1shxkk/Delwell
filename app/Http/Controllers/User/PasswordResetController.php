<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordChangedMail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotForm()
    {
        return view('user.auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'We couldn\'t find an account with that email address.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        // Check if user's email is verified
        if (!$user->email_verified) {
            $message = 'Please verify your email address first before resetting your password.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return back()->withErrors(['email' => $message]);
        }

        // Check rate limiting (1 request per minute)
        $recentRequest = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recentRequest) {
            $message = 'Please wait before requesting another password reset email.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 429);
            }
            return back()->withErrors(['email' => $message]);
        }

        // Generate reset token
        $token = Str::random(64);

        // Delete any existing tokens for this email
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Create new password reset record
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        try {
            // Send password reset email
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));

            $message = 'Password reset link sent to your email address!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Password reset email failed: ' . $e->getMessage());
            
            $message = 'Failed to send password reset email. Please try again later.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            return back()->withErrors(['email' => $message]);
        }
    }

    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('user.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.exists' => 'We couldn\'t find an account with that email address.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Find the password reset record
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            $message = 'Invalid or expired password reset token.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return back()->withErrors(['email' => $message]);
        }

        // Check if token is valid
        if (!Hash::check($request->token, $passwordReset->token)) {
            $message = 'Invalid password reset token.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return back()->withErrors(['token' => $message]);
        }

        // Check if token is expired (24 hours)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            // Delete expired token
            DB::table('password_resets')->where('email', $request->email)->delete();
            
            $message = 'Password reset token has expired. Please request a new one.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return back()->withErrors(['token' => $message]);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        try {
            // Send password changed confirmation email
            Mail::to($user->email)->send(new PasswordChangedMail($user));
        } catch (\Exception $e) {
            Log::error('Password changed email failed: ' . $e->getMessage());
            // Don't fail the password reset if email fails
        }

        $message = 'Your password has been reset successfully! You can now log in with your new password.';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('login')->with('success', $message);
    }
}
