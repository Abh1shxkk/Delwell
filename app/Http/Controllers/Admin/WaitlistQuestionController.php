<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaitlistQuestion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WaitlistQuestionController extends Controller
{
    /**
     * Display the questions management page
     */
    public function index(Request $request)
    {
        // Get statistics
        $stats = [
            'total' => WaitlistQuestion::count(),
            'active' => WaitlistQuestion::where('is_active', true)->count(),
            'inactive' => WaitlistQuestion::where('is_active', false)->count(),
        ];

        // If this is an AJAX request for DataTables
        if ($request->ajax() || $request->get('format') === 'json') {
            return $this->getDataTableData($request);
        }

        // Return view for regular page load
        return view('admin.waitlist-questions.index', compact('stats'));
    }

    /**
     * Get data for DataTables
     */
    private function getDataTableData(Request $request)
    {
        // Return just stats if requested
        if ($request->get('stats_only')) {
            return response()->json([
                'stats' => [
                    'total' => WaitlistQuestion::count(),
                    'active' => WaitlistQuestion::where('is_active', true)->count(),
                    'inactive' => WaitlistQuestion::where('is_active', false)->count(),
                ]
            ]);
        }

        $query = WaitlistQuestion::query();

        // Handle DataTables search
        if ($request->has('search') && !empty($request->get('search')['value'])) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('question_text', 'like', "%{$searchValue}%")
                  ->orWhere('field_name', 'like', "%{$searchValue}%")
                  ->orWhere('question_type', 'like', "%{$searchValue}%");
            });
        }

        // Handle column ordering
        if ($request->has('order')) {
            $orderColumn = $request->get('order')[0]['column'];
            $orderDirection = $request->get('order')[0]['dir'];
            
            $columns = ['id', 'question_text', 'question_type', 'field_name', 'is_required', 'is_active', 'sort_order', 'actions'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        // Get total records count
        $totalRecords = WaitlistQuestion::count();
        $filteredRecords = $query->count();

        // Handle pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        $questions = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $data = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'field_name' => $question->field_name,
                'is_required' => $question->is_required,
                'is_active' => $question->is_active,
                'sort_order' => $question->sort_order,
                'options' => $question->options,
                'max_selections' => $question->max_selections,
                'placeholder' => $question->placeholder,
                'help_text' => $question->help_text,
                'actions' => $question->id
            ];
        });

        return response()->json([
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new question
     */
    public function create()
    {
        return view('admin.waitlist-questions.create');
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:text,textarea,radio,checkbox,email',
            'field_name' => 'required|string|max:255|unique:waitlist_questions,field_name',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:500',
            'is_required' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
            'sort_order' => 'required|integer|min:0',
            'max_selections' => 'nullable|integer|min:1',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string|max:500',
        ]);

        // Convert string values to boolean
        $validated['is_required'] = $validated['is_required'] === '1';
        $validated['is_active'] = $validated['is_active'] === '1';

        // Clean up options array
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($value) {
                return !empty(trim($value));
            });
            $validated['options'] = array_values($validated['options']); // Re-index array
        }

        $question = WaitlistQuestion::create($validated);

        Log::info("Admin created waitlist question: {$question->field_name}");

        return redirect()->route('admin.waitlist-questions.index')
            ->with('success', 'Question created successfully.');
    }

    /**
     * Show the form for editing a question
     */
    public function edit($id)
    {
        $question = WaitlistQuestion::findOrFail($id);
        return view('admin.waitlist-questions.edit', compact('question'));
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, $id)
    {
        $question = WaitlistQuestion::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:text,textarea,radio,checkbox,email',
            'field_name' => 'required|string|max:255|unique:waitlist_questions,field_name,' . $id,
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:500',
            'is_required' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
            'sort_order' => 'required|integer|min:0',
            'max_selections' => 'nullable|integer|min:1',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string|max:500',
        ]);

        // Convert string values to boolean
        $validated['is_required'] = $validated['is_required'] === '1';
        $validated['is_active'] = $validated['is_active'] === '1';

        // Clean up options array
        if (isset($validated['options'])) {
            $validated['options'] = array_filter($validated['options'], function($value) {
                return !empty(trim($value));
            });
            $validated['options'] = array_values($validated['options']); // Re-index array
        }

        $question->update($validated);

        Log::info("Admin updated waitlist question: {$question->field_name}");

        return redirect()->route('admin.waitlist-questions.index')
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Toggle question active status
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $question = WaitlistQuestion::findOrFail($id);
            $question->is_active = !$question->is_active;
            $question->save();

            Log::info("Admin toggled waitlist question status: {$question->field_name} - Active: " . ($question->is_active ? 'Yes' : 'No'));

            return response()->json([
                'success' => true,
                'message' => 'Question status updated successfully.',
                'is_active' => $question->is_active
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to toggle question status: ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update question status.'
            ], 500);
        }
    }

    /**
     * Remove the specified question
     */
    public function destroy($id)
    {
        try {
            $question = WaitlistQuestion::findOrFail($id);
            $fieldName = $question->field_name;
            $question->delete();

            Log::info("Admin deleted waitlist question: {$fieldName}");

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete question: ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question.'
            ], 500);
        }
    }

    /**
     * Get question details for viewing
     */
    public function show($id)
    {
        try {
            $question = WaitlistQuestion::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'question' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found.'
            ], 404);
        }
    }
}
