<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizSectionController extends Controller
{
    /**
     * Display a listing of the sections.
     */
    public function index()
    {
        $sections = QuizSection::orderBy('order', 'asc')->get();
        
        return response()->json([
            'data' => $sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'description' => $section->description,
                    'order' => $section->order,
                    'is_active' => $section->is_active,
                    'actions' => $section->id
                ];
            })
        ]);
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:quiz_sections,name',
            'description' => 'nullable|string',
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
            $section = QuizSection::create([
                'name' => $request->name,
                'description' => $request->description,
                'order' => $request->order ?? 0,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create section'
            ], 500);
        }
    }

    /**
     * Display the specified section.
     */
    public function show($id)
    {
        try {
            $section = QuizSection::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:quiz_sections,name,' . $id,
            'description' => 'nullable|string',
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
            $section = QuizSection::findOrFail($id);
            
            $section->update([
                'name' => $request->name,
                'description' => $request->description,
                'order' => $request->order ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section'
            ], 500);
        }
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy($id)
    {
        try {
            $section = QuizSection::findOrFail($id);
            
            // Check if section has questions
            if ($section->questions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete section with existing questions'
                ], 400);
            }
            
            $section->delete();

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section'
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified section.
     */
    public function toggleStatus($id)
    {
        try {
            $section = QuizSection::findOrFail($id);
            $section->is_active = !$section->is_active;
            $section->save();

            return response()->json([
                'success' => true,
                'message' => 'Section status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section status'
            ], 500);
        }
    }

    /**
     * Get active sections for dropdown
     */
    public function getActiveSections()
    {
        $sections = QuizSection::active()->ordered()->get(['id', 'name']);
        
        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }
}
