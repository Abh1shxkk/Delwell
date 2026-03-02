<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizSection;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizResultsController extends Controller
{
    /**
     * Display a listing of quiz results.
     */
    public function index(Request $request)
    {
        // Statistics - only for users with role 'user'
        $totalUsers = User::where('role', 'user')->count();
        
        $usersWithResults = User::where('role', 'user')
            ->whereNotNull('quiz_results')
            ->whereRaw("quiz_results IS NOT NULL AND quiz_results != 'null' AND quiz_results != '[]' AND quiz_results != '{}' AND LENGTH(quiz_results) > 2")
            ->count();
        
        $usersWithoutResults = $totalUsers - $usersWithResults;
        
        $usersWithDelMatch = User::where('role', 'user')
            ->whereNotNull('del_match_code')
            ->where('del_match_code', '!=', '')
            ->count();

        $stats = [
            'total_users' => $totalUsers,
            'users_with_results' => $usersWithResults,
            'users_without_results' => $usersWithoutResults,
            'users_with_del_match' => $usersWithDelMatch,
        ];

        // Initial load - get first page
        $results = $this->getResults($request);

        return view('admin.quiz-results.index', compact('results', 'stats'));
    }

    /**
     * AJAX search endpoint for live search.
     */
    public function search(Request $request)
    {
        $results = $this->getResults($request);
        
        // Build HTML for the cards
        $html = view('admin.quiz-results.partials.results-cards', compact('results'))->render();
        
        // Build pagination HTML
        $paginationHtml = view('admin.quiz-results.partials.pagination', compact('results'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'pagination' => $paginationHtml,
            'total' => $results->total(),
            'from' => $results->firstItem() ?? 0,
            'to' => $results->lastItem() ?? 0,
        ]);
    }

    /**
     * Get filtered and paginated results.
     */
    private function getResults(Request $request)
    {
        $query = User::where('role', 'user')
            ->whereNotNull('quiz_results')
            ->whereRaw("quiz_results IS NOT NULL AND quiz_results != 'null' AND quiz_results != '[]' AND quiz_results != '{}' AND LENGTH(quiz_results) > 2")
            ->orderBy('updated_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('del_match_code', 'like', "%{$search}%");
            });
        }

        return $query->paginate(12);
    }

    /**
     * Show the quiz result details for a specific user.
     */
    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        
        // Ensure quiz_results is properly decoded
        $quizResults = $user->quiz_results;
        
        // Handle if quiz_results is a string (double-encoded JSON)
        if (is_string($quizResults)) {
            $decoded = json_decode($quizResults, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $quizResults = $decoded;
            }
        }

        // Extract answers from the calculated profile structure
        $answers = $this->extractAnswersFromResults($quizResults);

        // Get all questions ordered and group them by their section KEY from the questions table
        // This avoids duplicate sections from the sections table
        $allQuestions = QuizQuestion::active()->ordered()->get();
        
        // Group questions by section name
        $questionsBySection = $allQuestions->groupBy('section');
        
        // Get section descriptions from sections table (for reference only)
        $sectionDescriptions = QuizSection::pluck('description', 'name')->toArray();
        
        // Organize results by section
        $organizedResults = [];
        
        foreach ($questionsBySection as $sectionName => $questions) {
            $sectionData = [
                'section_name' => $sectionName,
                'section_description' => $sectionDescriptions[$sectionName] ?? null,
                'questions' => []
            ];
            
            foreach ($questions as $question) {
                $answer = null;
                $formattedAnswer = null;
                
                // Check if user has an answer for this question
                if (!empty($answers) && isset($answers[$question->question_id])) {
                    $answer = $answers[$question->question_id];
                    // Format the answer with option text if possible
                    $formattedAnswer = $this->formatAnswer($answer, $question);
                }
                
                $sectionData['questions'][] = [
                    'question_id' => $question->question_id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'answer' => $formattedAnswer,
                    'raw_answer' => $answer
                ];
            }
            
            // Add section (even if no answers, to show all questions)
            $organizedResults[] = $sectionData;
        }
        
        // Count answered questions
        $answeredCount = 0;
        $totalQuestions = 0;
        foreach ($organizedResults as $section) {
            foreach ($section['questions'] as $q) {
                $totalQuestions++;
                if ($q['answer'] !== null) {
                    $answeredCount++;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'del_match_code' => $user->del_match_code,
                'profile_image' => $user->profile_image,
                'ai_avatar_path' => $user->ai_avatar_path,
                'created_at' => $user->created_at->format('M d, Y h:i A'),
                'updated_at' => $user->updated_at->format('M d, Y h:i A'),
            ],
            'sections' => $organizedResults,
            'total_answers' => $answeredCount,
            'total_questions' => $totalQuestions,
            'quiz_summary' => $this->buildQuizSummary($quizResults)
        ]);
    }

    /**
     * Get quiz results history for a specific user.
     */
    public function history($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        // Get history from quiz_results_history column
        $history = $user->quiz_results_history;

        // Handle if it's a string (double-encoded JSON)
        if (is_string($history)) {
            $decoded = json_decode($history, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $history = $decoded;
            }
        }

        if (!is_array($history) || empty($history)) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                ],
                'history' => [],
                'total_submissions' => 0,
            ]);
        }

        // Get all questions for mapping answers
        $allQuestions = QuizQuestion::active()->ordered()->get();
        $questionsBySection = $allQuestions->groupBy('section');
        $sectionDescriptions = QuizSection::pluck('description', 'name')->toArray();

        // Process each historical entry
        $processedHistory = [];
        foreach (array_reverse($history) as $index => $entry) {
            $answers = $this->extractAnswersFromResults($entry);

            // Organize results by section
            $organizedResults = [];
            foreach ($questionsBySection as $sectionName => $questions) {
                $sectionData = [
                    'section_name' => $sectionName,
                    'section_description' => $sectionDescriptions[$sectionName] ?? null,
                    'questions' => []
                ];

                foreach ($questions as $question) {
                    $answer = null;
                    $formattedAnswer = null;

                    if (!empty($answers) && isset($answers[$question->question_id])) {
                        $answer = $answers[$question->question_id];
                        $formattedAnswer = $this->formatAnswer($answer, $question);
                    }

                    $sectionData['questions'][] = [
                        'question_id' => $question->question_id,
                        'question' => $question->question,
                        'type' => $question->type,
                        'answer' => $formattedAnswer,
                        'raw_answer' => $answer,
                    ];
                }

                $organizedResults[] = $sectionData;
            }

            // Count answered questions
            $answeredCount = 0;
            $totalQuestions = 0;
            foreach ($organizedResults as $section) {
                foreach ($section['questions'] as $q) {
                    $totalQuestions++;
                    if ($q['answer'] !== null) {
                        $answeredCount++;
                    }
                }
            }

            $processedHistory[] = [
                'index' => $index + 1,
                'submitted_at' => $entry['submitted_at'] ?? null,
                'archived_at' => $entry['archived_at'] ?? null,
                'del_match_code' => $entry['del_match_code'] ?? null,
                'attachment_style' => $entry['attachment_style_name'] ?? ($entry['attachment_style'] ?? null),
                'sections' => $organizedResults,
                'total_answers' => $answeredCount,
                'total_questions' => $totalQuestions,
                'quiz_summary' => $this->buildQuizSummary($entry),
            ];
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ],
            'history' => $processedHistory,
            'total_submissions' => count($processedHistory),
        ]);
    }

    /**
     * Delete a specific quiz history entry for a user.
     */
    public function deleteHistory($id, $historyIndex)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $history = $user->quiz_results_history;
        if (is_string($history)) {
            $history = json_decode($history, true);
        }

        if (!is_array($history) || empty($history)) {
            return response()->json(['success' => false, 'message' => 'No history found.'], 404);
        }

        // The history is displayed newest-first (reversed), so convert display index back to actual index
        $actualIndex = count($history) - 1 - (int) $historyIndex;

        if ($actualIndex < 0 || $actualIndex >= count($history)) {
            return response()->json(['success' => false, 'message' => 'Invalid history entry.'], 404);
        }

        // Remove the entry
        array_splice($history, $actualIndex, 1);

        $user->quiz_results_history = json_encode($history);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'History entry deleted successfully.',
            'remaining' => count($history),
        ]);
    }

    /**
     * Extract answers from the profile-based quiz results structure
     */
    private function extractAnswersFromResults($quizResults)
    {
        if (!is_array($quizResults)) {
            return [];
        }

        $answers = [];
        
        // Del Match Code™ questions (q1-q5) - stored as letters in del_match_code
        if (isset($quizResults['del_match_code']) && strlen($quizResults['del_match_code']) >= 5) {
            $code = $quizResults['del_match_code'];
            for ($i = 0; $i < 5; $i++) {
                $answers['q' . ($i + 1)] = $code[$i] ?? null;
            }
        }

        // Attachment Style questions (q6-q9) - the result is stored, need to infer
        if (isset($quizResults['attachment_style'])) {
            // Can't directly map back, but we know they answered these questions
            // We'll use the result as indication
            $answers['attachment_result'] = $quizResults['attachment_style'];
        }

        // Family Imprint questions (q10-q14)
        if (isset($quizResults['family_imprint']) && is_array($quizResults['family_imprint'])) {
            $familyMapping = [
                'love_style' => 'q10',
                'conflict_style' => 'q11',
                'emotion_style' => 'q12',
                'relationship_message' => 'q13',
                'current_impact' => 'q14'
            ];
            foreach ($familyMapping as $key => $qId) {
                if (isset($quizResults['family_imprint'][$key])) {
                    $answers[$qId] = $quizResults['family_imprint'][$key];
                }
            }
        }

        // Values Alignment questions (q15-q19)
        if (isset($quizResults['values_profile']) && is_array($quizResults['values_profile'])) {
            if (isset($quizResults['values_profile']['all_values']) && is_array($quizResults['values_profile']['all_values'])) {
                $valuesAnswers = $quizResults['values_profile']['all_values'];
                for ($i = 0; $i < count($valuesAnswers) && $i < 5; $i++) {
                    $answers['q' . (15 + $i)] = $valuesAnswers[$i];
                }
            }
        }

        // Energy Style questions (q20-q24)
        if (isset($quizResults['energy_profile']) && is_array($quizResults['energy_profile'])) {
            $energyMapping = [
                'social_energy' => 'q20',
                'structure_preference' => 'q21',
                'stress_response' => 'q22',
                'group_preference' => 'q23',
                'natural_rhythm' => 'q24'
            ];
            foreach ($energyMapping as $key => $qId) {
                if (isset($quizResults['energy_profile'][$key])) {
                    $answers[$qId] = $quizResults['energy_profile'][$key];
                }
            }
        }

        // Practical Compatibility questions (q25-q38)
        if (isset($quizResults['practical_profile']) && is_array($quizResults['practical_profile'])) {
            foreach ($quizResults['practical_profile'] as $key => $value) {
                // Keys are already in q25, q26 format
                if (preg_match('/^q\d+$/', $key)) {
                    $answers[$key] = $value;
                }
            }
        }

        // Prefer direct raw answers if available
        if (isset($quizResults['all_answers']) && is_array($quizResults['all_answers'])) {
            foreach ($quizResults['all_answers'] as $key => $value) {
                $answers[$key] = $value;
            }
        }

        // Also check if there's an 'answers' key with direct question mapping
        if (isset($quizResults['answers']) && is_array($quizResults['answers'])) {
            foreach ($quizResults['answers'] as $key => $value) {
                $answers[$key] = $value;
            }
        }

        // Keep readiness state as metadata without overriding real q39 answers.
        if (isset($quizResults['is_ready']) && !isset($answers['q39'])) {
            $answers['readiness_result'] = $quizResults['is_ready'] ? 'ready' : 'not_ready';
        }

        return $answers;
    }

    /**
     * Build a summary of the quiz results for display
     */
    private function buildQuizSummary($quizResults)
    {
        if (!is_array($quizResults)) {
            return null;
        }

        $summary = [];

        if (isset($quizResults['del_match_code'])) {
            $summary['del_match_code'] = $quizResults['del_match_code'];
        }

        if (isset($quizResults['attachment_style_name'])) {
            $summary['attachment_style'] = $quizResults['attachment_style_name'];
        } elseif (isset($quizResults['attachment_style'])) {
            $styleNames = ['A' => 'Secure', 'B' => 'Anxious', 'C' => 'Avoidant', 'D' => 'Disorganized'];
            $summary['attachment_style'] = $styleNames[$quizResults['attachment_style']] ?? $quizResults['attachment_style'];
        }

        if (isset($quizResults['values_profile']['primary_value'])) {
            $summary['primary_value'] = $quizResults['values_profile']['primary_value'];
        }

        if (isset($quizResults['is_ready'])) {
            $summary['ready_for_dating'] = $quizResults['is_ready'] ? 'Yes' : 'Not Yet';
        }

        if (isset($quizResults['quiz_completed_at'])) {
            $summary['completed_at'] = $quizResults['quiz_completed_at'];
        }

        return $summary;
    }

    /**
     * Format answer based on question options
     */
    private function formatAnswer($answer, $question)
    {
        if ($answer === null) {
            return null;
        }

        $options = $question->options ?? [];
        
        // If answer is an array (multiple choice)
        if (is_array($answer)) {
            $formattedValues = [];
            foreach ($answer as $val) {
                $formattedValues[] = $this->getOptionText($val, $options);
            }
            return implode(', ', $formattedValues);
        }
        
        // Single value
        return $this->getOptionText($answer, $options);
    }

    /**
     * Get option text from value
     */
    private function getOptionText($value, $options)
    {
        if (empty($options) || !is_array($options)) {
            return $value;
        }
        
        // First, try to match by option value
        foreach ($options as $option) {
            if (isset($option['value']) && $option['value'] == $value) {
                return $option['text'] ?? $value;
            }
            if (isset($option['text']) && $option['text'] == $value) {
                return $option['text'];
            }
        }
        
        // If value is a single letter (A, B, C, D, E), try to match by position
        // A = first option (index 0), B = second option (index 1), etc.
        if (strlen($value) === 1 && preg_match('/^[A-E]$/i', $value)) {
            $positionIndex = ord(strtoupper($value)) - ord('A'); // A=0, B=1, C=2, etc.
            if (isset($options[$positionIndex])) {
                return $options[$positionIndex]['text'] ?? $value;
            }
        }
        
        return $value;
    }

    /**
     * Format raw answer value
     */
    private function formatRawAnswer($value)
    {
        if (is_array($value)) {
            return implode(', ', array_map(function($v) {
                return is_array($v) ? json_encode($v) : $v;
            }, $value));
        }
        
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        
        return $value;
    }
}

