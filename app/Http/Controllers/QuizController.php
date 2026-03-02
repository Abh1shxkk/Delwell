<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Get quiz questions from database
     */
    private function getQuizQuestions()
    {
        // Fetch questions from database, ordered by section and order
        $questions = QuizQuestion::active()
            ->ordered()
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

        // If no questions in database, return empty array
        if (empty($questions)) {
            return [];
        }

        return $questions;
    }

    
    /**
     * Show the quiz introduction
     */
    public function index()
    {
        return view('quiz.introduction');
    }

    /**
     * Start the quiz
     */
    public function start()
    {
        Session::forget('quiz_answers');
        Session::forget('quiz_results');
        
        $questions = $this->getQuizQuestions();
        Session::put('quiz_questions', $questions);
        
        return view('quiz.quiz', compact('questions'));
    }

    /**
     * Submit quiz answers and calculate results
     */
    public function submit(Request $request)
    {
        $answers = $request->input('answers', []);
        $requiredAnswers = QuizQuestion::active()->count();
        
        if (empty($answers) || count($answers) < $requiredAnswers) {
            return redirect()->back()->withErrors(['error' => 'Please answer all questions.']);
        }

        // Store answers in session
        Session::put('quiz_answers', $answers);
        
        // Calculate results
        $results = $this->calculateResults($answers);
        Session::put('quiz_results', $results);
        
        return view('quiz.results', compact('results'));
    }

    /**
     * Calculate quiz results and Del Match Code
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
            'answers' => $answers, // Store ALL raw answers for admin panel
        ];
    }

    /**
     * Get attachment style name
     */
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

    /**
     * Calculate values profile
     */
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

    /**
     * Calculate energy profile
     */
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

    /**
     * Calculate family imprint
     */
    private function calculateFamilyImprint($answers)
    {
        $familyAnswers = [];
        for ($i = 10; $i <= 14; $i++) {
            if (isset($answers["q{$i}"])) {
                $familyAnswers[] = $answers["q{$i}"];
            }
        }
        
        return [
            'love_style' => $answers['q10'] ?? 'unknown',
            'conflict_style' => $answers['q11'] ?? 'unknown',
            'emotion_style' => $answers['q12'] ?? 'unknown',
            'relationship_message' => $answers['q13'] ?? 'unknown',
            'current_impact' => $answers['q14'] ?? 'unknown'
        ];
    }

    /**
     * Calculate practical profile
     */
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
     * Show signup quiz (Del Match Code™ only)
     */
    public function signupQuiz()
    {
        // Get only Del Match Code questions (first 5 questions)
        $questions = $this->getQuizQuestions();
        $delMatchQuestions = array_filter($questions, function($question) {
            return $question['section'] === 'Del Match Code™';
        });
        
        // Reset array keys
        $delMatchQuestions = array_values($delMatchQuestions);
        
        return view('quiz.signup-quiz', compact('delMatchQuestions'));
    }

    /**
     * Submit signup quiz (Del Match Code™ only)
     */
    public function submitSignupQuiz(Request $request)
    {
        $answers = $request->input('answers', []);
        $avatarData = $request->input('avatar', []);
        
        if (count($answers) < 5) {
            return redirect()->back()->withErrors(['error' => 'Please answer all questions.']);
        }

        // Validate avatar data
        $request->validate([
            'avatar.build' => 'required|string|max:100',
            'avatar.skin' => 'required|string|max:100',
            'avatar.hair' => 'required|string|max:100',
            'avatar.eyes' => 'required|string|max:100',
        ]);

        // Calculate Del Match Code from first 5 questions
        $delMatchCode = '';
        for ($i = 1; $i <= 5; $i++) {
            $delMatchCode .= $answers["q{$i}"] ?? '';
        }

        // Store results in session
        $results = [
            'del_match_code' => $delMatchCode,
            'answers' => $answers,
            'avatar_data' => $avatarData,
            'quiz_completed_at' => now(),
        ];

        Session::put('signup_quiz_results', $results);

        // Redirect to registration form
        return redirect()->route('register');
    }

    /**
     * Proceed to registration after quiz completion
     */
    public function proceedToRegistration()
    {
        if (!Session::has('quiz_results')) {
            return redirect()->route('quiz.start')->withErrors(['error' => 'Please complete the quiz first.']);
        }

        $results = Session::get('quiz_results');
        
        // Check if user is ready for dating
        if (!$results['is_ready']) {
            return view('quiz.not-ready');
        }

        return redirect()->route('register');
    }

    /**
     * Start post-registration quiz for authenticated users
     */
    public function userQuizStart()
    {
        $user = Auth::guard('user')->user();
        
        // Check if user already has a DelWell match code
        if ($user->del_match_code) {
            return redirect()->route('user.dashboard')
                ->with('info', 'You have already completed your DelWell Match Code™ quiz.');
        }
        
        // Get only Del Match Code questions (first 5 questions)
        $questions = $this->getQuizQuestions();
        $delMatchQuestions = array_filter($questions, function($question) {
            return $question['section'] === 'Del Match Code™';
        });
        
        // Reset array keys
        $delMatchQuestions = array_values($delMatchQuestions);
        
        return view('quiz.user-quiz', compact('delMatchQuestions'));
    }

    /**
     * Complete post-registration quiz for authenticated users
     */
    public function userQuizComplete(Request $request)
    {
        $user = Auth::guard('user')->user();
        $answers = $request->input('answers', []);
        $avatarData = $request->input('avatar', []);
        
        if (count($answers) < 5) {
            return redirect()->back()->withErrors(['error' => 'Please answer all questions.']);
        }

        // Validate profile data and avatar data
        $request->validate([
            'age' => 'required|integer|min:18|max:100',
            'gender' => 'required|in:male,female,other',
            'interested_in' => 'required|in:men,women,both',
            'avatar.build' => 'required|string|max:100',
            'avatar.skin' => 'required|string|max:100',
            'avatar.hair' => 'required|string|max:100',
            'avatar.eyes' => 'required|string|max:100',
        ]);

        // Calculate Del Match Code from first 5 questions
        $delMatchCode = '';
        for ($i = 1; $i <= 5; $i++) {
            $delMatchCode .= $answers["q{$i}"] ?? '';
        }

        // Store results
        $results = [
            'del_match_code' => $delMatchCode,
            'answers' => $answers,
            'avatar_data' => $avatarData,
            'quiz_completed_at' => now(),
        ];

        // Update user with profile info and quiz results
        User::where('id', $user->id)->update([
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'interested_in' => $request->input('interested_in'),
            'del_match_code' => $delMatchCode,
            'quiz_results' => json_encode($results),
            'avatar_data' => json_encode($avatarData),
        ]);

        // Generate AI avatar
        $registrationController = new \App\Http\Controllers\RegistrationController();
        $registrationController->generateUserAvatar($user, $avatarData);

        // Update profile completion
        $user = User::find($user->id); // Refresh user instance
        User::where('id', $user->id)->update([
            'profile_completion' => $this->calculateUserProfileCompletion($user)
        ]);

        // Redirect to dashboard with success message
        return redirect()->route('user.dashboard')
            ->with('success', 'Congratulations! Your DelWell Match Code™ is ' . $delMatchCode . '. Your personalized AI avatar has been generated.');
    }

    /**
     * Calculate profile completion for existing user
     */
    private function calculateUserProfileCompletion($user)
    {
        $totalQuizQuestions = \App\Models\QuizQuestion::active()->count();
        $completedQuizQuestions = $user->del_match_code ? 5 : 0;
        
        $completion = 0;
        
        // Basic registration completion (30%)
        $registrationFields = 10;
        $completedRegistrationFields = 6; // Required fields
        
        // Optional registration fields
        if (!empty($user->phone)) $completedRegistrationFields++;
        if (!empty($user->state)) $completedRegistrationFields++;
        if (!empty($user->bio)) $completedRegistrationFields++;
        if (!empty($user->relationship_type)) $completedRegistrationFields++;
        
        $registrationCompletion = ($completedRegistrationFields / $registrationFields) * 30;
        $completion += $registrationCompletion;
        
        // Quiz completion (70% based on actual questions answered)
        if ($totalQuizQuestions > 0) {
            $quizCompletion = ($completedQuizQuestions / $totalQuizQuestions) * 70;
            $completion += $quizCompletion;
        }
        
        return round($completion);
    }
}
