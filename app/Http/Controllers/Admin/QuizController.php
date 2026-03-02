<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display quiz management page
     */
    public function index()
    {
        return view('admin.registration.addquiz');
    }

    /**
     * Get all questions for DataTable (AJAX)
     */
    public function getQuestions(Request $request)
    {
        $questions = QuizQuestion::orderBy('order')->orderBy('id')->get();
        
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $questions->count(),
            'recordsFiltered' => $questions->count(),
            'data' => $questions->map(function ($question, $index) {
                return [
                    'id' => $question->id,
                    'question_id' => $question->question_id,
                    'section' => $question->section,
                    'question' => $question->question,
                    'options_count' => count($question->options),
                    'order' => $question->order,
                    'is_active' => $question->is_active,
                    'actions' => $question->id
                ];
            })
        ]);
    }

    /**
     * Store new question (AJAX)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question_id' => 'required|string|unique:quiz_questions,question_id',
                'section' => 'required|string|max:255',
                'question' => 'required|string',
                'options' => 'required|array|min:2',
                'options.*.text' => 'required|string',
                'options.*.value' => 'required|string',
                'order' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle order conflicts - if order already exists, find next available
            $requestedOrder = $request->order ?? 0;
            $existingOrder = QuizQuestion::where('order', $requestedOrder)->exists();
            
            if ($existingOrder && $requestedOrder > 0) {
                // Find the next available order number
                $maxOrder = QuizQuestion::max('order');
                $requestedOrder = $maxOrder + 1;
            }

            $question = QuizQuestion::create([
                'question_id' => $request->question_id,
                'section' => $request->section,
                'question' => $request->question,
                'options' => $request->options,
                'order' => $requestedOrder,
                'is_active' => true
            ]);

            error_log('Question created successfully: ' . $question->id);

            $message = 'Question added successfully!';
            if ($requestedOrder != ($request->order ?? 0)) {
                $message .= ' Order was adjusted to ' . $requestedOrder . ' to avoid conflicts.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $question
            ]);
            
        } catch (\Exception $e) {
            error_log('Quiz Store Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add question: ' . $e->getMessage()
            ], 500);
        }
        
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|string',
            'section' => 'required|string|max:255',
            'question' => 'required|string',
            'options' => 'required|array|min:1',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            error_log('Quiz Store Validation Errors: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $question = QuizQuestion::create([
                'question_id' => $request->question_id,
                'section' => $request->section,
                'question' => $request->question,
                'options' => $request->options,
                'order' => $request->order ?? 0,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question added successfully!',
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show question details (AJAX)
     */
    public function show($id)
    {
        try {
            $question = QuizQuestion::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }
    }

    /**
     * Update question (AJAX)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|string|unique:quiz_questions,question_id,' . $id,
            'section' => 'required|string|max:255',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.value' => 'required|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $question = QuizQuestion::findOrFail($id);
            
            // Handle order conflicts for updates
            $requestedOrder = $request->order ?? 0;
            $existingOrder = QuizQuestion::where('order', $requestedOrder)
                                        ->where('id', '!=', $id)
                                        ->exists();
            
            if ($existingOrder && $requestedOrder > 0) {
                // Find the next available order number
                $maxOrder = QuizQuestion::max('order');
                $requestedOrder = $maxOrder + 1;
                error_log("Order conflict detected during update. Using order: " . $requestedOrder);
            }
            
            $question->update([
                'question_id' => $request->question_id,
                'section' => $request->section,
                'question' => $request->question,
                'options' => $request->options,
                'order' => $requestedOrder
            ]);

            $message = 'Question updated successfully!';
            if ($requestedOrder != ($request->order ?? 0)) {
                $message .= ' Order was adjusted to ' . $requestedOrder . ' to avoid conflicts.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete question (AJAX)
     */
    public function destroy($id)
    {
        try {
            $question = QuizQuestion::findOrFail($id);
            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle question active status (AJAX)
     */
    public function toggleStatus($id)
    {
        try {
            $question = QuizQuestion::findOrFail($id);
            $question->update([
                'is_active' => !$question->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question status updated successfully!',
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}
