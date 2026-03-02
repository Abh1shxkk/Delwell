<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('user')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if profile is complete
        if (!$this->isProfileComplete($user)) {
            // Redirect to appropriate onboarding step
            return redirect()->route('user.onboarding.index')
                ->with('info', 'Please complete your profile to access this feature.');
        }
        
        return $next($request);
    }
    
    /**
     * Check if user profile is complete
     */
    private function isProfileComplete($user): bool
    {
        // Step 1: Basic profile info
        if (!$user->age || !$user->gender_identity || !$user->city) {
            return false;
        }
        
        // Step 2: Relationship preferences
        if (!$user->relationship_type || !$user->age_min || !$user->age_max) {
            return false;
        }
        
        // Step 3: Lifestyle info
        if (!$user->occupation || !$user->education || !$user->physical_activity) {
            return false;
        }
        
        // Step 4: Deeper quiz completion
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        if (!isset($quizResults['is_ready'])) {
            return false;
        }
        
        return true;
    }
}
