<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\QuizQuestion;
use App\Models\CircleInvitation;
use App\Models\CircleMember;
use App\Models\UserMatch;
use App\Services\MatchingService;

class DashboardController extends Controller
{
    public function index(MatchingService $matchingService)
    {
        $user = User::find(Auth::guard('user')->id());
        
        // Check if user has completed full quiz
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        $hasCompletedFullQuiz = isset($quizResults['is_ready']);
        
        // Get top matches for dashboard
        $topMatches = [];
        if ($hasCompletedFullQuiz || true) { // Allow for testing even without completed quiz
            $potentialMatches = $matchingService->getPotentialMatches($user, 5);
            
            // If no matches from service, get sample users with gender filter
            if ($potentialMatches->isEmpty()) {
                $fallbackQuery = User::where('id', '!=', $user->id)
                    ->where('role', 'user');
                
                // Apply gender preference filter
                if ($user->interested_in && $user->interested_in !== 'both') {
                    $genderMap = ['men' => 'male', 'women' => 'female'];
                    $legacyGender = $genderMap[$user->interested_in] ?? null;
                    $genderIdentity = $user->interested_in;
                    
                    $fallbackQuery->where(function ($q) use ($legacyGender, $genderIdentity) {
                        if ($legacyGender) {
                            $q->where('gender', $legacyGender);
                        }
                        $q->orWhere('gender_identity', $genderIdentity);
                    });
                }
                
                $potentialMatches = $fallbackQuery->limit(5)
                    ->get()
                    ->map(function ($match) use ($matchingService, $user) {
                        $match->match_percentage = $matchingService->calculateMatchPercentage($user, $match);
                        return $match;
                    });
            }
            
            // Process top 3 matches for dashboard display
            $topMatches = $potentialMatches->take(3)->map(function ($match) use ($user) {
                // Get Del Match Code from quiz results
                $matchQuiz = $match->quiz_results ? json_decode($match->quiz_results, true) : [];
                $delMatchCode = $matchQuiz['del_match_code'] ?? 'N/A';
                
                // Get display name (first name + last initial)
                $nameParts = explode(' ', $match->name);
                $displayName = $nameParts[0] ?? 'Unknown';
                if (isset($nameParts[1])) {
                    $displayName .= ' ' . substr($nameParts[1], 0, 1) . '.';
                }
                
                // Determine profile image - only use actual images and construct proper URL
                $profileImage = null;
                $profileImagePath = null;
                if ($match->profile_image && !str_contains($match->profile_image, 'pexels.com')) {
                    $profileImagePath = $match->profile_image;
                    $profileImage = url('storage/' . $match->profile_image);
                } elseif ($match->ai_avatar_path) {
                    $profileImagePath = $match->ai_avatar_path;
                    $profileImage = url('storage/' . $match->ai_avatar_path);
                }
                
                return [
                    'id' => $match->id,
                    'name' => $displayName,
                    'profile_image' => $profileImage,
                    'profile_image_path' => $profileImagePath,
                    'has_profile_image' => $profileImage !== null,
                    'del_match_code' => $delMatchCode,
                    'match_percentage' => $match->match_percentage ?? rand(75, 95),
                    'has_intro_video' => !empty($match->intro_video_path),
                    'intro_video_path' => $match->intro_video_path,
                ];
            })->toArray();
        }
        
        // Get user's circle members count
        $circleCount = CircleMember::where('user_id', $user->id)->count();
        
        return view('user.dashboard', compact('user', 'topMatches', 'hasCompletedFullQuiz', 'circleCount'));
    }

    public function discovery(MatchingService $matchingService)
    {
        $user = User::find(Auth::guard('user')->id());
        
        // Check if user has completed full quiz
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        $hasCompletedFullQuiz = isset($quizResults['is_ready']);
        
        // For testing, allow discovery even without completed quiz
        if (!$hasCompletedFullQuiz) {
            $hasCompletedFullQuiz = true;
        }
        
        // Get potential matches using the matching service
        $potentialMatches = collect();
        $matches = [];
        
        if ($hasCompletedFullQuiz) {
            $potentialMatches = $matchingService->getPotentialMatches($user, 10);
            
            // If no matches from service, get sample users with gender filter
            if ($potentialMatches->isEmpty()) {
                $fallbackQuery = User::where('id', '!=', $user->id)
                    ->where('role', 'user');
                
                // Apply gender preference filter
                if ($user->interested_in && $user->interested_in !== 'both') {
                    $genderMap = ['men' => 'male', 'women' => 'female'];
                    $legacyGender = $genderMap[$user->interested_in] ?? null;
                    $genderIdentity = $user->interested_in;
                    
                    $fallbackQuery->where(function ($q) use ($legacyGender, $genderIdentity) {
                        if ($legacyGender) {
                            $q->where('gender', $legacyGender);
                        }
                        $q->orWhere('gender_identity', $genderIdentity);
                    });
                }
                
                $potentialMatches = $fallbackQuery->get()
                    ->map(function ($match) use ($matchingService, $user) {
                        $match->match_percentage = $matchingService->calculateMatchPercentage($user, $match);
                        return $match;
                    });
            }
            
            // Process matches and add additional data
            $firstMatchId = $potentialMatches->first()->id ?? null;
            $matches = $potentialMatches->take(3)->map(function ($match) use ($matchingService, $user, $firstMatchId) {
                // Generate alignment description
                $alignmentDescription = $matchingService->generateAlignmentDescription($user, $match);
                
                // Get Del Match Code from quiz results
                $matchQuiz = $match->quiz_results ? json_decode($match->quiz_results, true) : [];
                $delMatchCode = $matchQuiz['del_match_code'] ?? 'N/A';
                
                // Check actual match status
                $matchStatus = UserMatch::getMatchStatus($user->id, $match->id);
                $reverseMatchStatus = UserMatch::getMatchStatus($match->id, $user->id);
                $isMutualMatch = $matchStatus === 'pending' && $reverseMatchStatus === 'pending';
                
                // Check if this is a circle pick (marked in database or first match)
                $circlePickMatch = UserMatch::where('user_id', $user->id)
                    ->where('matched_user_id', $match->id)
                    ->where('is_circle_pick', true)
                    ->first();
                $isCirclePick = $circlePickMatch !== null || $firstMatchId === $match->id;
                
                // Get display name (first name + last initial)
                $nameParts = explode(' ', $match->name);
                $displayName = $nameParts[0] ?? 'Unknown';
                if (isset($nameParts[1])) {
                    $displayName .= ' ' . substr($nameParts[1], 0, 1) . '.';
                }
                
                // Determine profile image - only use actual images, not placeholder URLs
                $profileImage = null;
                if ($match->profile_image && !str_contains($match->profile_image, 'pexels.com')) {
                    $profileImage = $match->profile_image;
                } elseif ($match->ai_avatar_path) {
                    $profileImage = $match->ai_avatar_path;
                }
                
                return [
                    'id' => $match->id,
                    'name' => $displayName,
                    'age' => $match->age ?? 25,
                    'bio' => $match->bio ?? 'No bio available yet.',
                    'profile_image' => $profileImage,
                    'has_profile_image' => $profileImage !== null,
                    'intro_video_path' => $match->intro_video_path,
                    'has_intro_video' => !empty($match->intro_video_path),
                    'del_match_code' => $delMatchCode,
                    'match_percentage' => $match->match_percentage,
                    'alignment_description' => $alignmentDescription,
                    'is_circle_pick' => $isCirclePick,
                    'match_status' => $matchStatus,
                    'is_mutual_match' => $isMutualMatch,
                    'city' => $match->city ?? 'Unknown',
                    'state' => $match->state ?? ''
                ];
            })->toArray();
        }
        
        return view('user.discovery', compact('hasCompletedFullQuiz', 'matches'));
    }

    /**
     * Flag interest in another user
     */
    public function flagInterest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $currentUser = Auth::guard('user')->user();
        $targetUserId = $request->user_id;

        // Check if already flagged
        $existingMatch = UserMatch::where('user_id', $currentUser->id)
            ->where('matched_user_id', $targetUserId)
            ->first();

        if ($existingMatch) {
            return response()->json([
                'success' => false,
                'message' => 'Already flagged interest in this user'
            ]);
        }

        // Create the match record
        $match = UserMatch::create([
            'user_id' => $currentUser->id,
            'matched_user_id' => $targetUserId,
            'status' => 'pending'
        ]);

        // Check if it's a mutual match
        $reverseMatch = UserMatch::where('user_id', $targetUserId)
            ->where('matched_user_id', $currentUser->id)
            ->where('status', 'pending')
            ->first();

        $isMutualMatch = $reverseMatch !== null;

        if ($isMutualMatch) {
            // Update both records to matched status
            $match->update(['status' => 'matched', 'matched_at' => now()]);
            $reverseMatch->update(['status' => 'matched', 'matched_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'is_mutual_match' => $isMutualMatch,
            'message' => $isMutualMatch ? "It's a match!" : 'Interest flagged successfully'
        ]);
    }

    /**
     * Schedule a date with a matched user
     */
    public function scheduleDate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $currentUser = Auth::guard('user')->user();
        $targetUserId = $request->user_id;

        // Verify they are mutually matched
        $isMutualMatch = UserMatch::areMatched($currentUser->id, $targetUserId);

        if (!$isMutualMatch) {
            return response()->json([
                'success' => false,
                'message' => 'You are not matched with this user'
            ]);
        }

        // For now, just return success - you can implement actual scheduling logic later
        return response()->json([
            'success' => true,
            'message' => 'Date scheduling feature coming soon!'
        ]);
    }

    public function selfDiscovery()
    {
        // Get user if authenticated, otherwise null for guests
        $user = Auth::guard('user')->check() ? User::find(Auth::guard('user')->id()) : null;
        
        return view('user.self-discovery', compact('user'));
    }

    /**
     * Show continue quiz page
     */
    public function continueQuiz()
    {
        $user = Auth::guard('user')->user();
        
        // If user already has Del Match Code, they can take the deeper profile quiz
        if ($user->del_match_code) {
            // Get questions that are NOT Del Match Code questions (the deeper quiz)
            $deeperQuestions = QuizQuestion::where('is_active', true)
                ->where('section', '!=', 'Del Match Code™')
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            if ($deeperQuestions->isEmpty()) {
                return redirect()->route('user.dashboard')
                    ->with('info', 'No additional quiz questions are available at the moment. Your profile is complete!');
            }

            $questionsArray = [];
            foreach ($deeperQuestions as $question) {
                $options = $question->options;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                
                $questionsArray[] = [
                    'id' => $question->question_id,
                    'section' => $question->section,
                    'question' => $question->question,
                    'options' => $options ?? []
                ];
            }

            // Use a simple view instead of the problematic continue-quiz view
            return view('quiz.deeper-quiz', compact('questionsArray'));
        }
        
        // If user doesn't have Del Match Code yet, redirect to start the quiz
        return redirect()->route('user.quiz.start');
    }

    /**
     * Submit continued quiz
     */
    public function submitQuiz(Request $request)
    {
        $user = Auth::guard('user')->user();
        $answers = $request->input('answers', []);
        
        // Get existing Del Match Code answers from user's current quiz_results
        $existingResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        $existingAnswers = $existingResults['all_answers'] ?? $existingResults['answers'] ?? [];
        
        // If user doesn't have existing answers in results, reconstruct Del Match Code answers
        if (empty($existingAnswers) && $user->del_match_code) {
            // Reconstruct the Del Match Code answers (q1-q5) from the stored code
            $delMatchCode = $user->del_match_code;
            for ($i = 0; $i < strlen($delMatchCode) && $i < 5; $i++) {
                $existingAnswers["q" . ($i + 1)] = $delMatchCode[$i];
            }
        }
        
        // Merge existing answers with new deeper quiz answers
        $allAnswers = array_merge($existingAnswers, $answers);
        
        // Just ensure we have the new answers for the deeper quiz
        if (empty($answers)) {
            return redirect()->back()->withErrors(['error' => 'Please answer all questions.']);
        }

        // Calculate full results
        $results = $this->calculateResults($allAnswers);
        
        // Calculate new profile completion percentage based on actual quiz questions
        $totalQuizQuestions = QuizQuestion::active()->count();
        $completedQuizQuestions = count($allAnswers); // All 39 questions now completed
        
        // Profile completion components:
        // 1. Basic registration fields (30% of total completion)
        // 2. Quiz questions (70% of total completion)
        
        // Assume registration is complete (30%) + full quiz completion (70%)
        $registrationCompletion = 30; // Assume registration fields are complete
        $quizCompletion = ($totalQuizQuestions > 0) ? ($completedQuizQuestions / $totalQuizQuestions) * 70 : 0;
        $newCompletion = round($registrationCompletion + $quizCompletion);
        
        // Ensure $user is a valid Eloquent model instance
        if ($user && $user instanceof \App\Models\User) {
            // Save current quiz_results to history before overwriting
            $history = $user->quiz_results_history ? (is_array($user->quiz_results_history) ? $user->quiz_results_history : json_decode($user->quiz_results_history, true)) : [];
            if (!is_array($history)) {
                $history = [];
            }
            
            // If there are existing results, archive them
            if ($user->quiz_results) {
                $previousResults = is_array($user->quiz_results) ? $user->quiz_results : json_decode($user->quiz_results, true);
                if (!empty($previousResults)) {
                    $previousResults['archived_at'] = now()->toDateTimeString();
                    $history[] = $previousResults;
                }
            }
            
            // Add submission timestamp to current results
            $results['submitted_at'] = now()->toDateTimeString();
            
            $user->quiz_results = json_encode($results);
            $user->quiz_results_history = json_encode($history);
            $user->profile_completion = $newCompletion;
            $user->save();
        }
        
        // Clear session data
        Session::forget('existing_answers');
        
        return redirect()->route('user.dashboard')
            ->with('success', 'Quiz completed! Your full compatibility profile is now ready.');
    }

    /**
     * Calculate quiz results (copied from QuizController)
     */
    private function calculateResults($answers)
    {
        // Calculate Del Match Code (first 5 questions)
        $delMatchCode = '';
        for ($i = 1; $i <= 5; $i++) {
            $delMatchCode .= $answers["q{$i}"] ?? '';
        }

        // Calculate Attachment Style (questions 6-9)
        $attachmentScores = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        for ($i = 6; $i <= 9; $i++) {
            $answer = $answers["q{$i}"] ?? '';
            if (isset($attachmentScores[$answer])) {
                $attachmentScores[$answer]++;
            }
        }
        $attachmentStyle = array_keys($attachmentScores, max($attachmentScores))[0];
        $attachmentStyleName = $this->getAttachmentStyleName($attachmentStyle);

        // Calculate Values Alignment (questions 15-19)
        $valuesProfile = $this->calculateValuesProfile($answers);

        // Calculate Energy Style (questions 20-24)
        $energyProfile = $this->calculateEnergyProfile($answers);

        // Calculate Family Imprint Score
        $familyImprint = $this->calculateFamilyImprint($answers);

        // Calculate Practical Compatibility Score
        $practicalProfile = $this->calculatePracticalProfile($answers);

        // Check readiness
        $isReady = $this->determineReadiness($answers);

        return [
            'del_match_code' => $delMatchCode,
            'attachment_style' => $attachmentStyle,
            'attachment_style_name' => $attachmentStyleName,
            'values_profile' => $valuesProfile,
            'energy_profile' => $energyProfile,
            'family_imprint' => $familyImprint,
            'practical_profile' => $practicalProfile,
            'is_ready' => $isReady,
            'quiz_completed_at' => now(),
            'all_answers' => $answers,
            'answers' => $answers,
        ];
    }

    // Helper methods (copied from QuizController)
    private function getAttachmentStyleName($style)
    {
        $styles = [
            'A' => 'Secure',
            'B' => 'Anxious',
            'C' => 'Avoidant',
            'D' => 'Disorganized'
        ];
        return $styles[$style] ?? 'Unknown';
    }

    private function calculateValuesProfile($answers)
    {
        $values = [];
        for ($i = 15; $i <= 19; $i++) {
            if (isset($answers["q{$i}"])) {
                $values[] = $answers["q{$i}"];
            }
        }
        
        $valueCounts = array_count_values($values);
        arsort($valueCounts);
        
        return [
            'primary_value' => array_keys($valueCounts)[0] ?? 'unknown',
            'all_values' => $values
        ];
    }

    private function calculateEnergyProfile($answers)
    {
        return [
            'social_energy' => $answers['q20'] ?? 'unknown',
            'structure_preference' => $answers['q21'] ?? 'unknown',
            'stress_response' => $answers['q22'] ?? 'unknown',
            'group_preference' => $answers['q23'] ?? 'unknown',
            'natural_rhythm' => $answers['q24'] ?? 'unknown'
        ];
    }

    private function calculateFamilyImprint($answers)
    {
        return [
            'love_style' => $answers['q10'] ?? 'unknown',
            'conflict_style' => $answers['q11'] ?? 'unknown',
            'emotion_style' => $answers['q12'] ?? 'unknown',
            'relationship_message' => $answers['q13'] ?? 'unknown',
            'current_impact' => $answers['q14'] ?? 'unknown'
        ];
    }

    private function calculatePracticalProfile($answers)
    {
        $practical = [];
        foreach ($answers as $questionId => $value) {
            if (!is_string($questionId)) {
                continue;
            }

            if (preg_match('/^q(\d+)$/', $questionId, $matches)) {
                if ((int) $matches[1] >= 25) {
                    $practical[$questionId] = $value;
                }
                continue;
            }

            // Include non-standard ids (e.g., RF*) from newer quiz sets.
            $practical[$questionId] = $value;
        }
        
        return $practical;
    }

    /**
     * Determine readiness from submitted answers.
     */
    private function determineReadiness($answers)
    {
        foreach ($answers as $answer) {
            if ($answer === 'ready') {
                return true;
            }

            if ($answer === 'not_ready') {
                return false;
            }
        }

        // If no dedicated readiness question exists, keep users unblocked.
        return true;
    }

    /**
     * Show Circle page
     */
    public function circle()
    {
        $user = Auth::guard('user')->user();
        
        // Get user's circle members with their details
        $circleMembers = CircleMember::where('user_id', $user->id)
            ->with(['member', 'invitation'])
            ->orderBy('joined_at', 'desc')
            ->get()
            ->map(function ($circleMember) {
                return [
                    'id' => $circleMember->id,
                    'name' => $circleMember->member->name,
                    'email' => $circleMember->member->email,
                    'relationship' => $circleMember->relationship,
                    'joined_at' => $circleMember->joined_at->format('M d, Y'),
                    'profile_image' => $circleMember->member->profile_image,
                ];
            });
        
        // Get circle recommendations (for now, empty - will add insights later)
        $circleRecommendations = [];
        
        // Get pending invitations
        $pendingInvitations = CircleInvitation::where('inviter_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.circle', compact('user', 'circleMembers', 'circleRecommendations', 'pendingInvitations'));
    }

    /**
     * Invite someone to circle
     */
    public function inviteToCircle(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255'
        ]);

        $user = Auth::guard('user')->user();
        
        try {
            // Check if invitation already exists for this email and user
            $existingInvite = CircleInvitation::where('inviter_id', $user->id)
                ->where('invitee_email', $request->email)
                ->where('status', 'pending')
                ->first();
                
            if ($existingInvite && !$existingInvite->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => "An invitation has already been sent to {$request->email}."
                ]);
            }
            
            // Create invitation record
            $invitation = CircleInvitation::create([
                'inviter_id' => $user->id,
                'invitee_name' => $request->name,
                'invitee_email' => $request->email,
                'relationship' => $request->relationship,
                'token' => CircleInvitation::generateToken(),
                'expires_at' => now()->addDays(30), // 30 days expiry
            ]);
            
            // Generate accept URL
            $acceptUrl = route('user.circle.accept', ['token' => $invitation->token]);
            
            // Send email
            Mail::send('emails.circle-invite', [
                'inviteeName' => $request->name,
                'inviterName' => $user->name,
                'relationship' => $request->relationship,
                'acceptUrl' => $acceptUrl,
            ], function ($message) use ($request, $user) {
                $message->to($request->email, $request->name)
                        ->subject($user->name . ' invited you to join their DelWell Circle');
            });
            
            Log::info('Circle invitation sent', [
                'inviter_id' => $user->id,
                'invitee_email' => $request->email,
                'invitation_id' => $invitation->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Invitation sent to {$request->email}! They will receive an email to join your circle."
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send circle invitation', [
                'error' => $e->getMessage(),
                'inviter_id' => $user->id,
                'invitee_email' => $request->email
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation. Please try again later.'
            ], 500);
        }
    }

    /**
     * Accept circle invitation
     */
    public function acceptCircleInvite($token)
    {
        try {
            // Find invitation by token
            $invitation = CircleInvitation::where('token', $token)
                ->where('status', 'pending')
                ->first();
                
            if (!$invitation) {
                return redirect()->route('invite.show')
                    ->with('error', 'Invalid or expired invitation link.');
            }
            
            if ($invitation->isExpired()) {
                return redirect()->route('invite.show')
                    ->with('error', 'This invitation has expired.');
            }
            
            // Redirect to invite code page with the token pre-filled
            return redirect()->route('invite.show', ['code' => $token])
                ->with('success', "You have been invited by {$invitation->inviter->name} to join their Circle! Enter the code below to continue.");
                
        } catch (\Exception $e) {
            Log::error('Failed to accept circle invitation', [
                'error' => $e->getMessage(),
                'token' => $token
            ]);
            
            return redirect()->route('invite.show')
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}
