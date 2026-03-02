<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Show onboarding wizard - determines which step user needs
     */
    public function index()
    {
        $user = Auth::guard('user')->user();
        
        // Determine current step
        $currentStep = $this->getCurrentStep($user);
        
        // If all steps complete, redirect to complete page
        if ($currentStep === 5) {
            return redirect()->route('user.onboarding.complete');
        }
        
        // Redirect to appropriate step
        return redirect()->route("user.onboarding.step{$currentStep}");
    }
    
    /**
     * Step 1: Basic Profile Info
     */
    public function step1()
    {
        $user = Auth::guard('user')->user();
        $progress = $this->calculateProgress($user);
        
        return view('user.onboarding.step1', compact('user', 'progress'));
    }
    
    /**
     * Save Step 1
     */
    public function saveStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer|min:18|max:100',
            'gender_identity' => 'required|in:women,men,nonbinary,prefer_not_to_say',
            'sexual_orientation' => 'required|in:heterosexual,lgbtq+,prefer_not_to_say',
            'interested_in' => 'required|in:men,women,both',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'bio' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = Auth::guard('user')->user();
        $user->update($request->only([
            'age', 'gender_identity', 'sexual_orientation', 'interested_in',
            'city', 'state', 'bio'
        ]));
        
        return redirect()->route('user.onboarding.step2')
            ->with('success', 'Basic profile saved! Let\'s continue...');
    }
    
    /**
     * Step 2: Relationship Preferences
     */
    public function step2()
    {
        $user = Auth::guard('user')->user();
        $progress = $this->calculateProgress($user);
        
        // Check if step 1 is complete
        if (!$user->age || !$user->gender_identity || !$user->city) {
            return redirect()->route('user.onboarding.step1')
                ->with('error', 'Please complete basic profile first.');
        }
        
        return view('user.onboarding.step2', compact('user', 'progress'));
    }
    
    /**
     * Save Step 2
     */
    public function saveStep2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'relationship_type' => 'required|in:serious,casual,friendship,open',
            'relationship_status' => 'required|in:single,divorced,separated,widowed,in_a_relationship,it_is_complicated',
            'has_children' => 'required|in:yes,no',
            'age_min' => 'required|integer|min:18|max:100',
            'age_max' => 'required|integer|min:18|max:100|gte:age_min',
            'distance_preference' => 'required|in:10,25,50,long',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = Auth::guard('user')->user();
        $user->update($request->only([
            'relationship_type', 'relationship_status', 'has_children',
            'age_min', 'age_max', 'distance_preference'
        ]));
        
        return redirect()->route('user.onboarding.step3')
            ->with('success', 'Relationship preferences saved!');
    }
    
    /**
     * Step 3: Lifestyle & Substance Use
     */
    public function step3()
    {
        $user = Auth::guard('user')->user();
        $progress = $this->calculateProgress($user);
        
        // Check if previous steps are complete
        if (!$user->relationship_type || !$user->age_min) {
            return redirect()->route('user.onboarding.step2')
                ->with('error', 'Please complete relationship preferences first.');
        }
        
        return view('user.onboarding.step3', compact('user', 'progress'));
    }
    
    /**
     * Save Step 3
     */
    public function saveStep3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'occupation' => 'required|in:psychologist,medical,wellness,entrepreneur,finance,software,engineer,artist,educator,attorney,real_estate,hospitality,beauty,student,parent,retired,other',
            'education' => 'required|in:less_than_bachelor,bachelor,master,doctorate,other',
            'physical_activity' => 'required|in:not_active,occasionally_active,active,fitness_lifestyle',
            'alcohol_use' => 'required|in:never,occasionally,socially,regularly',
            'cannabis_use' => 'required|in:never,occasionally,regularly',
            'smoking_vaping' => 'required|in:never,occasionally,regularly',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = Auth::guard('user')->user();
        $user->update($request->only([
            'occupation', 'education', 'physical_activity',
            'alcohol_use', 'cannabis_use', 'smoking_vaping'
        ]));
        
        return redirect()->route('user.onboarding.step4')
            ->with('success', 'Lifestyle information saved!');
    }
    
    /**
     * Step 4: Deeper Quiz (Full Compatibility Assessment)
     */
    public function step4()
    {
        $user = Auth::guard('user')->user();
        $progress = $this->calculateProgress($user);
        
        // Check if previous steps are complete
        if (!$user->occupation || !$user->education) {
            return redirect()->route('user.onboarding.step3')
                ->with('error', 'Please complete lifestyle information first.');
        }
        
        // Get deeper quiz questions (exclude Del Match Code questions)
        $questions = QuizQuestion::where('is_active', true)
            ->where('section', '!=', 'Del Match Code™')
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->map(function ($question) {
                $options = $question->options;
                
                // Ensure options is an array
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                
                // If still not array, make it empty array
                if (!is_array($options)) {
                    $options = [];
                }
                
                // Parse options - handle both formats
                $parsedOptions = [];
                foreach ($options as $key => $value) {
                    if (is_array($value)) {
                        // Format: {"text": "...", "value": "..."}
                        $text = $value['text'] ?? $value['label'] ?? '';
                        $val = $value['value'] ?? $key;
                        $parsedOptions[$val] = $text;
                    } else {
                        // Format: "A": "Some text"
                        $parsedOptions[$key] = $value;
                    }
                }
                
                return [
                    'id' => $question->question_id,
                    'section' => $question->section,
                    'question' => $question->question,
                    'options' => $parsedOptions
                ];
            })
            ->toArray();
        
        return view('user.onboarding.step4', compact('user', 'progress', 'questions'));
    }
    
    /**
     * Save Step 4 (Complete Onboarding)
     */
    public function saveStep4(Request $request)
    {
        $user = Auth::guard('user')->user();
        $answers = $request->input('answers', []);
        
        // Get existing Del Match Code answers
        $existingResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        $existingAnswers = $existingResults['answers'] ?? [];
        
        // Merge with new answers
        $allAnswers = array_merge($existingAnswers, $answers);
        
        // Calculate full results
        $results = $this->calculateQuizResults($allAnswers);
        
        // Save to history
        $history = $user->quiz_results_history ? json_decode($user->quiz_results_history, true) : [];
        if (!is_array($history)) {
            $history = [];
        }
        
        if (!empty($existingResults)) {
            $existingResults['archived_at'] = now()->toDateTimeString();
            $history[] = $existingResults;
        }
        
        // Update user
        $user->quiz_results = json_encode($results);
        $user->quiz_results_history = json_encode($history);
        $user->profile_completion = 100;
        $user->save();
        
        return redirect()->route('user.onboarding.complete')
            ->with('success', 'Onboarding complete! Welcome to DelWell!');
    }
    
    /**
     * Onboarding Complete Page
     */
    public function complete()
    {
        $user = Auth::guard('user')->user();
        
        return view('user.onboarding.complete', compact('user'));
    }
    
    /**
     * Determine current step for user
     */
    private function getCurrentStep($user): int
    {
        // Step 1: Basic profile
        if (!$user->age || !$user->gender_identity || !$user->city) {
            return 1;
        }
        
        // Step 2: Relationship preferences
        if (!$user->relationship_type || !$user->age_min || !$user->age_max) {
            return 2;
        }
        
        // Step 3: Lifestyle
        if (!$user->occupation || !$user->education || !$user->physical_activity) {
            return 3;
        }
        
        // Step 4: Deeper quiz
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        if (!isset($quizResults['is_ready'])) {
            return 4;
        }
        
        // All complete - redirect to complete page
        return 5;
    }
    
    /**
     * Calculate onboarding progress percentage
     */
    private function calculateProgress($user): int
    {
        $steps = 0;
        $completed = 0;
        
        // Step 1
        $steps++;
        if ($user->age && $user->gender_identity && $user->city) {
            $completed++;
        }
        
        // Step 2
        $steps++;
        if ($user->relationship_type && $user->age_min && $user->age_max) {
            $completed++;
        }
        
        // Step 3
        $steps++;
        if ($user->occupation && $user->education && $user->physical_activity) {
            $completed++;
        }
        
        // Step 4
        $steps++;
        $quizResults = $user->quiz_results ? json_decode($user->quiz_results, true) : [];
        if (isset($quizResults['is_ready'])) {
            $completed++;
        }
        
        return round(($completed / $steps) * 100);
    }
    
    /**
     * Calculate quiz results
     */
    private function calculateQuizResults($answers)
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
        $attachmentStyle = array_keys($attachmentScores, max($attachmentScores))[0] ?? 'A';

        // Calculate other profiles
        $valuesProfile = $this->calculateValuesProfile($answers);
        $energyProfile = $this->calculateEnergyProfile($answers);
        $familyImprint = $this->calculateFamilyImprint($answers);
        $practicalProfile = $this->calculatePracticalProfile($answers);
        $isReady = $this->determineReadiness($answers);

        return [
            'del_match_code' => $delMatchCode,
            'attachment_style' => $attachmentStyle,
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
    
    private function calculateValuesProfile($answers)
    {
        $values = [];
        for ($i = 15; $i <= 19; $i++) {
            if (isset($answers["q{$i}"])) {
                $values[] = $answers["q{$i}"];
            }
        }
        
        if (empty($values)) {
            return ['primary_value' => 'unknown', 'all_values' => []];
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
            }
        }
        return $practical;
    }
    
    private function determineReadiness($answers)
    {
        foreach ($answers as $answer) {
            if ($answer === 'ready') return true;
            if ($answer === 'not_ready') return false;
        }
        return true;
    }
}
