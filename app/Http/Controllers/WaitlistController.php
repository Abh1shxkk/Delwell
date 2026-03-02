<?php

namespace App\Http\Controllers;

use App\Models\WaitlistApplication;
use App\Models\WaitlistQuestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WaitlistController extends Controller
{
    public function showApplication()
    {
        $questions = WaitlistQuestion::active()->ordered()->get();
        return view('pages.waitlist-application', compact('questions'));
    }

    public function store(Request $request)
    {
        // Get active questions to build dynamic validation rules
        $questions = WaitlistQuestion::active()->get();
        $rules = [];
        $messages = [];
        $data = [];

        foreach ($questions as $question) {
            $fieldName = $question->field_name;
            $rule = [];

            // Add required rule
            if ($question->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            // Add type-specific rules
            switch ($question->question_type) {
                case 'email':
                    $rule[] = 'email';
                    $rule[] = 'unique:waitlist_applications,email';
                    $messages[$fieldName . '.unique'] = 'This email has already been submitted for the waitlist.';
                    break;
                case 'text':
                case 'textarea':
                    $rule[] = 'string';
                    $rule[] = 'max:1000';
                    break;
                case 'radio':
                    $rule[] = 'string';
                    break;
                case 'checkbox':
                    $rule[] = 'array';
                    if ($question->max_selections) {
                        $rule[] = 'max:' . $question->max_selections;
                        $messages[$fieldName . '.max'] = 'Please select no more than ' . $question->max_selections . ' option(s).';
                    }
                    $rules[$fieldName . '.*'] = 'string';
                    break;
            }

            $rules[$fieldName] = $rule;
        }

        $validated = $request->validate($rules, $messages);

        // Prepare data for database insertion
        $responses = [];
        $dbData = [];

        // Define which columns exist in the database and their types
        $dbColumns = [
            'email' => 'string',
            'draws_you' => 'string', 
            'relationship_with_self' => 'string',
            'values' => 'json',
            'statement' => 'string',
            'community_values' => 'json'
        ];

        foreach ($questions as $question) {
            $fieldName = $question->field_name;
            $dbColumn = str_replace('-', '_', $fieldName);
            
            if (isset($validated[$fieldName])) {
                $value = $validated[$fieldName];
                
                // Store in responses JSON for flexibility
                $responses[$fieldName] = $value;
                
                // Only map to database columns if they exist in the table
                if (array_key_exists($dbColumn, $dbColumns)) {
                    $columnType = $dbColumns[$dbColumn];
                    
                    if ($columnType === 'string') {
                        // Convert arrays to comma-separated strings for string columns
                        $dbData[$dbColumn] = is_array($value) ? implode(', ', $value) : $value;
                    } elseif ($columnType === 'json') {
                        // Store arrays directly for JSON columns
                        $dbData[$dbColumn] = is_array($value) ? $value : [$value];
                    }
                }
            }
        }

        // Store all responses in JSON format for future flexibility
        $dbData['responses'] = $responses;

        WaitlistApplication::create($dbData);

        return redirect()->route('waitlist.thank-you');
    }

    public function thankYou()
    {
        return view('pages.waitlist-thank-you');
    }
}
