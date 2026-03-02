<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EmailVerification;
use App\Mail\WelcomeMail;
use App\Models\User;

class EmailVerificationController extends Controller
{
    /**
     * Send welcome email after email verification
     * @param User $user
     */
    public function notice()
    {
        $user = Auth::guard('user')->user();
        
        // If email is already verified, redirect to dashboard
        if ($user && $user->email_verified) {
            return redirect()->route('user.dashboard')
                ->with('success', 'Your email is already verified!');
        }
        
        return view('user.email-verification.notice');
    }

    /**
     * Resend email verification
     */
    public function resend(Request $request)
    {
        $user = Auth::guard('user')->user();
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }
        
        if ($user->email_verified) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }
        
        try {
            // Check if user has requested too many times recently (rate limiting)
            $lastRequest = session('last_verification_request');
            if ($lastRequest && now()->diffInMinutes($lastRequest) < 1) {
                return response()->json([
                    'message' => 'Please wait before requesting another verification email.'
                ], 429);
            }
            
            // Regenerate token if expired
            /** @var User $user */
            if ($user->isEmailVerificationExpired()) {
                $user->generateEmailVerificationToken();
            }
            
            // Send the verification email
            Mail::to($user->email)->send(new EmailVerification($user));
            
            // Track the request time
            session(['last_verification_request' => now()]);
            
            Log::info('Email verification resent for user: ' . $user->id);
            
            return response()->json([
                'message' => 'Verification email sent successfully! Please check your inbox.',
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Email verification resend failed for user: ' . $user->id . ' - ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send verification email. Please try again later.',
                'success' => false
            ], 500);
        }
    }

    /**
     * Handle email verification from token
     */
    public function verify(Request $request, $token)
    {
        $user = \App\Models\User::where('email_verification_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Invalid verification token. The link may be expired or already used.']);
        }
        
        if ($user->email_verified) {
            // If user is logged in, redirect to dashboard
            if (Auth::guard('user')->check()) {
                return redirect()->route('user.dashboard')
                    ->with('info', 'Your email was already verified. Welcome back!');
            }
            
            // If not logged in, redirect to login
            return redirect()->route('login')
                ->with('info', 'Your email was already verified. Please log in to continue.');
        }
        
        // Check if token is expired
        /** @var User $user */
        if ($user->isEmailVerificationExpired()) {
            // Generate new token
            $user->generateEmailVerificationToken();
            
            // Send new verification email
            try {
                Mail::to($user->email)->send(new EmailVerification($user));
            } catch (\Exception $e) {
                Log::error('Failed to send new verification email: ' . $e->getMessage());
            }
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Verification link has expired. We\'ve sent you a new one! Please check your email.']);
        }
        
        // Mark email as verified  
        /** @var User $user */
        $user->markEmailAsVerified();
        
        Log::info('Email verified successfully for user: ' . $user->id);
        
        // Send welcome email after successful verification
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            Log::info('Welcome email sent to user: ' . $user->id);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email to user: ' . $user->id . ' - ' . $e->getMessage());
            // Don't fail the verification process if welcome email fails
        }
        
        // If user is not logged in, log them in
        if (!Auth::guard('user')->check()) {
            Auth::guard('user')->login($user);
        }
        
        // Check if profile is complete
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        $hasCompletedQuiz = isset($quizResults['is_ready']);
        
        // If profile is incomplete, redirect to onboarding
        if (!$user->age || !$user->gender_identity || !$user->city || 
            !$user->relationship_type || !$user->occupation || !$hasCompletedQuiz) {
            return redirect()->route('user.onboarding.index')
                ->with('success', 'Email verified successfully! Let\'s complete your profile to unlock all features. 🎉');
        }
        
        // If profile is complete, go to dashboard
        return redirect()->route('user.dashboard')
            ->with('success', 'Email verified successfully! Welcome back to DelWell. 🎉');
    }

    /**
     * Show verification success page
     */
    public function success()
    {
        $user = Auth::guard('user')->user();
        
        if (!$user || !$user->email_verified) {
            return redirect()->route('user.email-verification.notice');
        }
        
        return view('user.email-verification.success');
    }
}