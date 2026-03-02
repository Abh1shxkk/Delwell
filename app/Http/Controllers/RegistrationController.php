<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CircleInvitation;
use App\Models\CircleMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\EmailVerification;
use App\Services\GeminiAvatarService;

class RegistrationController extends Controller
{
    /**
     * Show invite code entry page
     */
    public function showInviteCodeForm()
    {
        return view('invite-code');
    }

    /**
     * Verify invite code and allow registration
     */
    public function verifyInviteCode(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string'
        ]);

        $inviteCode = strtoupper(trim($request->invite_code));
        
        // Check if it's a waitlist invite code
        $waitlistInvite = \App\Models\WaitlistApplication::where('invite_code', $inviteCode)
            ->where('status', 'approved')
            ->first();
        
        // Check if it's a circle invitation code
        $circleInvite = CircleInvitation::where('token', $inviteCode)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$waitlistInvite && !$circleInvite) {
            return redirect()->back()
                ->withErrors(['invite_code' => 'Invalid or expired invitation code.'])
                ->withInput();
        }
        
        // Store the verified invite code in session
        Session::put('verified_invite_code', $inviteCode);
        Session::put('invite_type', $waitlistInvite ? 'waitlist' : 'circle');
        
        if ($circleInvite) {
            Session::put('circle_invitation', [
                'inviter_id' => $circleInvite->inviter_id,
                'relationship' => $circleInvite->relationship,
                'invitee_email' => $circleInvite->invitee_email,
                'invitee_name' => $circleInvite->invitee_name
            ]);
        }
        
        return redirect()->route('register');
    }

    /**
     * Show the registration form
     */
    public function index()
    {
        // Check if user has verified invite code
        if (!Session::has('verified_invite_code')) {
            return redirect()->route('invite.show')
                ->withErrors(['error' => 'Please enter a valid invitation code first.']);
        }
        
        $signupResults = Session::get('signup_quiz_results', []);
        $inviteCode = Session::get('verified_invite_code');
        
        return view('register', compact('signupResults', 'inviteCode'));
    }

    /**
     * Handle user registration
     */
    public function store(Request $request)
    {
        // Check if user has verified invite code
        if (!Session::has('verified_invite_code')) {
            return redirect()->route('invite.show')
                ->withErrors(['error' => 'Please enter a valid invitation code first.']);
        }

        // Validate the registration data
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store registration data in session (don't create account yet)
        $registrationData = $request->all();
        $registrationData['invite_code'] = Session::get('verified_invite_code');
        Session::put('registration_data', $registrationData);
        
        // Redirect to quiz - account will be created after quiz completion
        return redirect()->route('registration.quiz');
    }

    /**
     * Show registration quiz (required to complete registration)
     */
    public function showRegistrationQuiz()
    {
        // Check if registration data exists in session
        if (!Session::has('registration_data')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Please complete the registration form first.']);
        }

        // Get only Del Match Code questions (first 5 questions)
        $questions = \App\Models\QuizQuestion::where('section', 'Del Match Code™')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->question_id,
                    'section' => $question->section,
                    'question' => $question->question,
                    'options' => $question->options
                ];
            })
            ->toArray();
        
        return view('quiz.registration-quiz', compact('questions'));
    }

    /**
     * Complete registration after quiz
     */
    public function completeRegistration(Request $request)
    {
        // Check if registration data exists in session
        if (!Session::has('registration_data')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Please complete the registration form first.']);
        }

        $registrationData = Session::get('registration_data');
        $answers = $request->input('answers', []);
        
        if (count($answers) < 5) {
            return redirect()->back()->withErrors(['error' => 'Please answer all questions.']);
        }

        // Calculate Del Match Code from first 5 questions
        $delMatchCode = '';
        for ($i = 1; $i <= 5; $i++) {
            $delMatchCode .= $answers["q{$i}"] ?? '';
        }

        // Store results without avatar data (streamlined flow)
        $results = [
            'del_match_code' => $delMatchCode,
            'answers' => $answers,
            'quiz_completed_at' => now(),
        ];

        try {
            // Create the user with registration data (profile info will be collected later)
            $user = $this->createUserWithQuizData($registrationData, $results);
            
            // Send email verification
            $this->sendEmailVerification($user);
            
            // Log the user in
            Auth::guard('user')->login($user);
            
            // Clear session data
            Session::forget('registration_data');
            Session::forget('verified_invite_code');
            Session::forget('invite_type');
            Session::forget('circle_invitation');
            
            // Redirect to results page first, then to dashboard
            return redirect()->route('registration.results')
                ->with('delMatchCode', $delMatchCode)
                ->with('success', 'Registration completed! Please check your email to verify your account.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Registration failed. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show registration results page
     */
    public function showResults()
    {
        $user = Auth::guard('user')->user();
        
        if (!$user || !$user->del_match_code) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Please complete registration first.']);
        }

        $delMatchCode = $user->del_match_code;
        
        return view('quiz.registration-results', compact('delMatchCode'));
    }

    /**
     * Validate registration data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Basic Information Only
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'max:30', 'unique:users'], // 'regex:/^[a-zA-Z0-9_]+$/' - commented to allow email as username
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'],
            
            // Terms and Preferences
            'accepted_terms' => ['required', 'accepted'],
            'email_notifications' => ['boolean'],
            'marketing_emails' => ['boolean'],
        ], [
            // Custom error messages
            'first_name.regex' => 'First name may only contain letters and spaces.',
            'last_name.regex' => 'Last name may only contain letters and spaces.',
            'username.regex' => 'Username may only contain letters, numbers, and underscores.',
            'password.regex' => 'Password must contain at least one letter and one number.',
            'accepted_terms.required' => 'You must accept the terms of service to continue.',
        ]);
    }

    /**
     * Create a new user instance with quiz data
     */
    protected function createUserWithQuizData(array $data, array $quizResults)
    {
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => null, // Will be collected later in profile completion
            'age' => null, // Will be collected later in profile completion
            'gender' => null, // Will be collected later in profile completion
            'city' => null, // Will be collected later in profile completion
            'state' => null, // Will be collected later in profile completion
            'bio' => null, // Will be collected later in profile completion
            'interested_in' => null, // Will be collected later in profile completion
            'age_min' => 18, // Default values
            'age_max' => 100, // Default values
            'relationship_type' => null, // Will be collected later
            'interests' => null, // Will be collected later
            'email_notifications' => isset($data['email_notifications']) ? 1 : 0,
            'marketing_emails' => isset($data['marketing_emails']) ? 1 : 0,
            'accepted_terms' => 1,
            'role' => 'user',
            'active' => 1,
            'status' => 0, // 0 = pending email verification
            'email_verification_token' => Str::random(64),
            'profile_completion' => 30, // Registration + quiz completed - 30% completion (profile info still needed)
            
            // Quiz results
            'del_match_code' => $quizResults['del_match_code'],
            'quiz_results' => json_encode($quizResults),
            
            // Avatar data - optional for streamlined flow
            'avatar_data' => isset($quizResults['avatar_data']) ? json_encode($quizResults['avatar_data']) : null,
        ]);

        // Check if this user was invited to any circles
        $this->handleCircleInvitations($user);

        return $user;
    }

    /**
     * Handle circle invitations for newly registered user
     */
    protected function handleCircleInvitations(User $user)
    {
        try {
            // Get the invite code from session (stored during registration)
            $registrationData = Session::get('registration_data', []);
            $inviteCode = $registrationData['invite_code'] ?? null;
            
            if (!$inviteCode) {
                Log::warning('No invite code found for user during circle invitation handling', [
                    'user_id' => $user->id
                ]);
                return;
            }
            
            // Find the invitation by token
            $invitation = CircleInvitation::where('token', $inviteCode)
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->first();

            if ($invitation) {
                // Create circle member relationship
                CircleMember::create([
                    'user_id' => $invitation->inviter_id, // The person who sent invitation
                    'member_id' => $user->id, // The new user joining the circle
                    'relationship' => $invitation->relationship,
                    'invitation_id' => $invitation->id,
                    'joined_at' => now(),
                ]);

                // Mark invitation as accepted
                $invitation->update([
                    'status' => 'accepted',
                    'accepted_at' => now()
                ]);

                Log::info('User automatically added to circle', [
                    'new_user_id' => $user->id,
                    'circle_owner_id' => $invitation->inviter_id,
                    'invitation_id' => $invitation->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to handle circle invitations', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create a new user instance
     */
    protected function createUser(array $data)
    {
        // Get signup quiz results from session (may be empty if registering first)
        $signupResults = Session::get('signup_quiz_results', []);
        
        return User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => null, // Will be collected later in profile completion
            'age' => null, // Will be collected in quiz
            'gender' => null, // Will be collected in quiz
            'city' => null, // Will be collected later in profile completion
            'state' => null, // Will be collected later in profile completion
            'bio' => null, // Will be collected later in profile completion
            'interested_in' => null, // Will be collected in quiz
            'age_min' => 18, // Default values
            'age_max' => 100, // Default values
            'relationship_type' => null, // Will be collected later
            'interests' => null, // Will be collected later
            'email_notifications' => isset($data['email_notifications']) ? 1 : 0,
            'marketing_emails' => isset($data['marketing_emails']) ? 1 : 0,
            'accepted_terms' => 1,
            'role' => 'user',
            'active' => 1,
            'status' => 0, // 0 = pending email verification
            'email_verification_token' => Str::random(64),
            'profile_completion' => 10, // Basic registration only - 10% completion
            
            // Quiz results - will be null initially if registering first
            'del_match_code' => $signupResults['del_match_code'] ?? null,
            'quiz_results' => !empty($signupResults) ? json_encode($signupResults) : null,
            
            // Avatar data - will be null initially if registering first
            'avatar_data' => isset($signupResults['avatar_data']) ? json_encode($signupResults['avatar_data']) : null,
        ]);
    }

    /**
     * Calculate profile completion percentage
     */
    protected function calculateProfileCompletion(array $data, array $signupResults = [])
    {
        // Get total number of quiz questions from database
        $totalQuizQuestions = \App\Models\QuizQuestion::active()->count();
        $completedQuizQuestions = !empty($signupResults) ? 5 : 0; // Del Match Code™ questions completed if quiz taken
        
        // Profile completion components:
        // 1. Basic registration fields (30% of total completion)
        // 2. Quiz questions (70% of total completion)
        
        $completion = 0;
        
        // Basic registration completion (30%)
        $registrationFields = 10; // Total registration fields
        $completedRegistrationFields = 6; // Required fields always filled
        
        // Optional registration fields
        if (!empty($data['phone'])) $completedRegistrationFields++;
        if (!empty($data['state'])) $completedRegistrationFields++;
        if (!empty($data['bio'])) $completedRegistrationFields++;
        if (!empty($data['relationship_type'])) $completedRegistrationFields++;
        
        $registrationCompletion = ($completedRegistrationFields / $registrationFields) * 30;
        $completion += $registrationCompletion;
        
        // Quiz completion (70% based on actual questions answered)
        if ($totalQuizQuestions > 0) {
            $quizCompletion = ($completedQuizQuestions / $totalQuizQuestions) * 70;
            $completion += $quizCompletion;
        }
        
        return round($completion);
    }

    /**
     * Send email verification
     */
    protected function sendEmailVerification(User $user)
    {
        try {
            // Send the verification email
            Mail::to($user->email)->send(new EmailVerification($user));
            
        } catch (\Exception $e) {
            // Log the error but don't fail registration
            Log::error('Email verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Verify email address (legacy route - redirects to new controller)
     */
    public function verifyEmail(Request $request, $token)
    {
        return redirect()->route('user.email-verification.verify', $token);
    }

    /**
     * Resend email verification (legacy route - redirects to new controller)
     */
    public function resendVerification(Request $request)
    {
        return redirect()->route('user.email-verification.resend');
    }

    /**
     * Generate AI avatar for user (called after quiz completion)
     */
    public function generateUserAvatar(User $user, array $avatarData = null)
    {
        try {
            // Use provided avatar data or get from session
            if (!$avatarData) {
                $signupResults = Session::get('signup_quiz_results', []);
                $avatarData = $signupResults['avatar_data'] ?? null;
            }
            
            if (!$avatarData) {
                Log::warning('No avatar data found for user: ' . $user->id);
                return;
            }
            
            // Initialize Gemini Avatar Service
            $avatarService = new GeminiAvatarService();
            
            // Generate avatar
            $result = $avatarService->generateAvatar(
                $avatarData,
                $user->age,
                $user->gender
            );
            
            if ($result['success']) {
                // Update user with avatar information
                $user->update([
                    'ai_avatar_path' => $result['avatar_path'],
                    'avatar_description' => $result['description'],
                    'avatar_generation_data' => [
                        'avatar_data' => $avatarData,
                        'age' => $user->age,
                        'gender' => $user->gender,
                        'generated_at' => now()->toISOString(),
                        'service' => 'gemini'
                    ]
                ]);
                
                Log::info('Avatar generated successfully for user: ' . $user->id);
            } else {
                Log::error('Avatar generation failed for user: ' . $user->id . ' - ' . ($result['error'] ?? 'Unknown error'));
            }
            
        } catch (\Exception $e) {
            Log::error('Avatar generation exception for user: ' . $user->id . ' - ' . $e->getMessage());
        }
    }
}