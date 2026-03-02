<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Mail\VerifyUser;
use App\Helpers\EmailHelper;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'    => 'required', 
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withInput()->withErrors($validator);
        }

        $loginInput = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if (!$user || $user->active == 0) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Account does not exist.'], 401);
            }
            return redirect()->back()->with('error', 'Account does not exist.');
        }

        // Check if user is blocked
        if ($user->is_blocked) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Your account has been blocked. Please contact support.'], 403);
            }
            return redirect()->back()->with('error', 'Your account has been blocked. Please contact support.');
        }

        if ($user->role === 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Access denied.'], 403);
            }
            return redirect()->back()->with('error', 'Access denied.');
        }

        $allowedRoles = ['user', 'manager'];
        if (!in_array($user->role, $allowedRoles)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized role.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized role.');
        }

        $guard = $user->role;
        $credentials = [
            filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $loginInput,
            'password' => $password,
        ];

        if (Auth::guard($guard)->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check email verification for users
            if ($user->role === 'user' && !$user->email_verified) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'redirect' => route('user.email-verification.notice'),
                        'message' => 'Please verify your email address to continue.'
                    ]);
                }
                
                return redirect()->route('user.email-verification.notice')
                    ->with('message', 'Please verify your email address to continue.');
            }
            
            // Check profile completion for users
            if ($user->role === 'user') {
                $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
                $hasCompletedQuiz = isset($quizResults['is_ready']);
                
                // If profile is incomplete, redirect to onboarding
                if (!$user->age || !$user->gender_identity || !$user->city || 
                    !$user->relationship_type || !$user->occupation || !$hasCompletedQuiz) {
                    
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'redirect' => route('user.onboarding.index'),
                            'message' => 'Please complete your profile to continue.'
                        ]);
                    }
                    
                    return redirect()->route('user.onboarding.index')
                        ->with('info', 'Welcome back! Please complete your profile to unlock all features.');
                }
            }
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('' . $user->role . '.dashboard');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'error' => 'Either email or password is incorrect.'], 401);
        }
        return redirect()->back()->with('error', 'Either email or password is incorrect.');
    }




    public function managerLogout(){
        Auth::guard('manager')->logout();
        return redirect()->route('login');
    }

    public function userLogout(){
        Auth::guard('user')->logout();
        return redirect()->route('invite');
    }
}