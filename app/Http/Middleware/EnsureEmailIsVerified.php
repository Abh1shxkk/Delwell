<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('user')->user();
        
        // If user is not authenticated, let other middleware handle it
        if (!$user) {
            return $next($request);
        }
        
        // If email is already verified, continue
        if ($user->email_verified) {
            return $next($request);
        }
        
        // Check if token is expired and regenerate if needed
        /** @var User $user */
        if ($user->isEmailVerificationExpired()) {
            $user->generateEmailVerificationToken();
            
            // Send new verification email
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)
                    ->send(new \App\Mail\EmailVerification($user));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Email verification resend failed: ' . $e->getMessage());
            }
        }
        
        // Allow access to specific routes for unverified users
        $allowedRoutes = [
            'user.email-verification.notice',
            'user.email-verification.resend',
            'email.verify',
            'user.logout',
            'logout',
            'user.profile-settings', // Allow user to update their email if needed
            'registration.results' // Allow viewing registration results
        ];
        
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }
        
        // Redirect to email verification notice
        return redirect()->route('user.email-verification.notice')
            ->with('message', 'Please verify your email address to continue.');
    }
}