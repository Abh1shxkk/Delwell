<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display user management page
     */
    public function index(Request $request)
    {
        // Get statistics (only 'user' role, exclude admin and manager)
        $stats = $this->getStats();

        // If this is an AJAX request for DataTables
        if ($request->ajax() || $request->get('format') === 'json') {
            return $this->getDataTableData($request, $stats);
        }

        // Return view for regular page load
        return view('admin.user-management.index', compact('stats'));
    }

    /**
     * Get data for DataTables
     */
    private function getDataTableData(Request $request, $stats = null)
    {
        // Return just stats if requested
        if ($request->get('stats_only')) {
            return response()->json([
                'stats' => $stats ?: $this->getStats()
            ]);
        }

        // Base query - only 'user' role (no managers or admins)
        $query = User::where('role', 'user');

        // Handle deleted filter
        $deletedFilter = $request->get('deleted_filter', 'active');
        if ($deletedFilter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($deletedFilter === 'all') {
            $query->withTrashed();
        }

        // Handle email verified filter
        if ($request->has('verified_filter') && !empty($request->get('verified_filter'))) {
            $verifiedFilter = $request->get('verified_filter');
            if ($verifiedFilter === 'verified') {
                $query->where('email_verified', true);
            } elseif ($verifiedFilter === 'unverified') {
                $query->where('email_verified', false);
            }
        }

        // Handle active status filter
        if ($request->has('status_filter') && !empty($request->get('status_filter'))) {
            $statusFilter = $request->get('status_filter');
            if ($statusFilter === 'active') {
                $query->where('active', 1);
            } elseif ($statusFilter === 'inactive') {
                $query->where('active', 0);
            }
        }

        // Handle blocked filter
        if ($request->has('blocked_filter') && !empty($request->get('blocked_filter'))) {
            $blockedFilter = $request->get('blocked_filter');
            if ($blockedFilter === 'blocked') {
                $query->where('is_blocked', true);
            } elseif ($blockedFilter === 'not_blocked') {
                $query->where('is_blocked', false);
            }
        }
        
        // Handle profile completion filter
        if ($request->has('profile_filter') && !empty($request->get('profile_filter'))) {
            $profileFilter = $request->get('profile_filter');
            if ($profileFilter === 'complete') {
                $query->where('profile_completion', 100);
            } elseif ($profileFilter === 'incomplete') {
                $query->where('profile_completion', '<', 100);
            } elseif ($profileFilter === 'high') {
                $query->where('profile_completion', '>=', 75)->where('profile_completion', '<', 100);
            } elseif ($profileFilter === 'medium') {
                $query->where('profile_completion', '>=', 50)->where('profile_completion', '<', 75);
            } elseif ($profileFilter === 'low') {
                $query->where('profile_completion', '<', 50);
            }
        }

        // Handle DataTables search
        if ($request->has('search') && !empty($request->get('search')['value'])) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('username', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhere('del_match_code', 'like', "%{$searchValue}%");
            });
        }

        // Handle column ordering
        if ($request->has('order')) {
            $orderColumn = $request->get('order')[0]['column'];
            $orderDirection = $request->get('order')[0]['dir'];
            
            $columns = ['id', 'name', 'email', 'active', 'email_verified', 'created_at', 'actions'];
            if (isset($columns[$orderColumn]) && $columns[$orderColumn] !== 'actions') {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get total records count
        $totalQuery = User::where('role', 'user');
        if ($deletedFilter === 'deleted') {
            $totalQuery->onlyTrashed();
        } elseif ($deletedFilter === 'all') {
            $totalQuery->withTrashed();
        }
        $totalRecords = $totalQuery->count();
        $filteredRecords = $query->count();

        // Handle pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        $users = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'active' => $user->active,
                'is_blocked' => $user->is_blocked,
                'email_verified' => $user->email_verified,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->toISOString(),
                'profile_image' => $user->profile_image,
                'ai_avatar_path' => $user->ai_avatar_path,
                'deleted_at' => $user->deleted_at ? $user->deleted_at->toISOString() : null,
                'city' => $user->city,
                'state' => $user->state,
                'gender_identity' => $user->gender_identity,
                'occupation' => $user->occupation,
                'education' => $user->education,
                'profile_completion' => $user->profile_completion ?? 0,
                'actions' => $user->id
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
     * Get statistics helper
     */
    private function getStats()
    {
        return [
            'total' => User::where('role', 'user')->count(),
            'verified' => User::where('role', 'user')->where('email_verified', true)->count(),
            'unverified' => User::where('role', 'user')->where('email_verified', false)->count(),
            'deleted' => User::where('role', 'user')->onlyTrashed()->count(),
            'active' => User::where('role', 'user')->where('active', 1)->count(),
            'inactive' => User::where('role', 'user')->where('active', 0)->count(),
            'blocked' => User::where('role', 'user')->where('is_blocked', true)->count(),
            'profile_complete' => User::where('role', 'user')->where('profile_completion', 100)->count(),
            'profile_incomplete' => User::where('role', 'user')->where('profile_completion', '<', 100)->count(),
            'avg_profile_completion' => round(User::where('role', 'user')->avg('profile_completion') ?? 0),
        ];
    }

    /**
     * Get user details for view modal (section-wise)
     */
    public function show($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'active' => $user->active,
                    'is_blocked' => $user->is_blocked,
                    'blocked_at' => $user->blocked_at ? $user->blocked_at->format('M d, Y H:i') : null,
                    'email_verified' => $user->email_verified,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : null,
                    'created_at' => $user->created_at->format('M d, Y H:i'),
                    'updated_at' => $user->updated_at->format('M d, Y H:i'),
                    'deleted_at' => $user->deleted_at ? $user->deleted_at->format('M d, Y H:i') : null,
                    'last_seen' => $user->last_seen ? $user->last_seen->format('M d, Y H:i') : null,
                    'last_active' => $user->last_active ? $user->last_active->format('M d, Y H:i') : null,
                    
                    // Profile Completion
                    'profile_completion' => $user->profile_completion ?? 0,
                    
                    // Basic Information
                    'bio' => $user->bio,
                    'age' => $user->age,
                    'gender_identity' => $user->gender_identity,
                    'sexual_orientation' => $user->sexual_orientation,
                    'interested_in' => $user->interested_in,
                    'relationship_type' => $user->relationship_type,
                    'age_min' => $user->age_min,
                    'age_max' => $user->age_max,
                    
                    // Relationship Context
                    'relationship_status' => $user->relationship_status,
                    'has_children' => $this->normalizeHasChildren($user->has_children),
                    
                    // Lifestyle
                    'city' => $user->city,
                    'state' => $user->state,
                    'distance_preference' => $user->distance_preference,
                    'occupation' => $user->occupation,
                    'education' => $user->education,
                    'physical_activity' => $user->physical_activity,
                    
                    // Substance Use
                    'alcohol_use' => $user->alcohol_use,
                    'cannabis_use' => $user->cannabis_use,
                    'smoking_vaping' => $user->smoking_vaping,
                    
                    // Media
                    'profile_image' => $user->profile_image,
                    'ai_avatar_path' => $user->ai_avatar_path,
                    'intro_video_path' => $user->intro_video_path,
                    
                    // Account
                    'is_premium' => $user->is_premium,
                    'premium_expires_at' => $user->premium_expires_at ? $user->premium_expires_at->format('M d, Y') : null,
                    'del_match_code' => $user->del_match_code,
                    
                    // Formatted values
                    'formatted_occupation' => $this->formatOccupation($user->occupation),
                    'formatted_education' => $this->formatEducation($user->education),
                    'formatted_relationship_status' => $this->formatRelationshipStatus($user->relationship_status),
                    'formatted_gender_identity' => $this->formatGenderIdentity($user->gender_identity),
                    'formatted_sexual_orientation' => $this->formatSexualOrientation($user->sexual_orientation),
                    'formatted_relationship_type' => $this->formatRelationshipType($user->relationship_type),
                    'formatted_distance' => $this->formatDistance($user->distance_preference),
                    'formatted_physical_activity' => $this->formatPhysicalActivity($user->physical_activity),
                    'formatted_alcohol' => $this->formatSubstanceUse($user->alcohol_use),
                    'formatted_cannabis' => $this->formatSubstanceUse($user->cannabis_use),
                    'formatted_smoking' => $this->formatSubstanceUse($user->smoking_vaping),
                    'formatted_children' => $this->formatHasChildren($user->has_children),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to fetch user details: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user details.'
            ], 500);
        }
    }

    /**
     * Get user data for edit modal (all fields)
     */
    public function edit($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'active' => $user->active,
                    'is_blocked' => $user->is_blocked,
                    'email_verified' => $user->email_verified,
                    'is_premium' => $user->is_premium,
                    
                    // Basic Information
                    'bio' => $user->bio,
                    'age' => $user->age,
                    'gender_identity' => $user->gender_identity,
                    'sexual_orientation' => $user->sexual_orientation,
                    'interested_in' => $user->interested_in,
                    'relationship_type' => $user->relationship_type,
                    'age_min' => $user->age_min,
                    'age_max' => $user->age_max,
                    
                    // Relationship Context
                    'relationship_status' => $user->relationship_status,
                    'has_children' => $this->normalizeHasChildren($user->has_children),
                    
                    // Lifestyle
                    'city' => $user->city,
                    'state' => $user->state,
                    'distance_preference' => $user->distance_preference,
                    'occupation' => $user->occupation,
                    'education' => $user->education,
                    'physical_activity' => $user->physical_activity,
                    
                    // Substance Use
                    'alcohol_use' => $user->alcohol_use,
                    'cannabis_use' => $user->cannabis_use,
                    'smoking_vaping' => $user->smoking_vaping,
                    
                    // Media
                    'profile_image' => $user->profile_image,
                    'intro_video_path' => $user->intro_video_path,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to fetch user for edit: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user data.'
            ], 500);
        }
    }

    /**
     * Update user details (all fields including media and password)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            $rules = [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'active' => 'required|boolean',
                'is_blocked' => 'required|boolean',
                'email_verified' => 'required|boolean',
                'is_premium' => 'required|boolean',
                
                // Basic Information
                'bio' => 'nullable|string|max:1000',
                'age' => 'nullable|integer|min:18|max:120',
                'gender_identity' => 'nullable|string|in:women,men,nonbinary,prefer_not_to_say',
                'sexual_orientation' => 'nullable|string|in:heterosexual,lgbtq+,prefer_not_to_say',
                'interested_in' => 'nullable|string|in:men,women,both',
                'relationship_type' => 'nullable|string|in:serious,casual,friendship,open',
                'age_min' => 'nullable|integer|min:18|max:100',
                'age_max' => 'nullable|integer|min:18|max:100',
                
                // Relationship Context
                'relationship_status' => 'nullable|string|in:single,divorced,separated,widowed,in_a_relationship,it_is_complicated',
                'has_children' => 'nullable|string|in:yes,no',
                
                // Lifestyle
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'distance_preference' => 'nullable|string|in:10,25,50,long',
                'occupation' => 'nullable|string|max:100',
                'education' => 'nullable|string|in:less_than_bachelor,bachelor,master,doctorate,other',
                'physical_activity' => 'nullable|string|in:not_active,occasionally_active,active,fitness_lifestyle',
                
                // Substance Use
                'alcohol_use' => 'nullable|string|in:never,occasionally,socially,regularly',
                'cannabis_use' => 'nullable|string|in:never,occasionally,regularly',
                'smoking_vaping' => 'nullable|string|in:never,occasionally,regularly',
                
                // Media
                'profile_image' => 'nullable|image|mimes:jpeg,png,gif|max:5120',
                'intro_video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv|max:51200',
                'remove_profile_image' => 'nullable|boolean',
                'remove_intro_video' => 'nullable|boolean',
                
                // Password
                'password' => 'nullable|string|min:8|confirmed',
            ];
            
            $validated = $request->validate($rules);

            // Handle email verification change
            if ($validated['email_verified'] && !$user->email_verified) {
                $validated['email_verified_at'] = now();
            } elseif (!$validated['email_verified'] && $user->email_verified) {
                $validated['email_verified_at'] = null;
            }

            // Handle blocking change
            if ($validated['is_blocked'] && !$user->is_blocked) {
                $validated['blocked_at'] = now();
            } elseif (!$validated['is_blocked'] && $user->is_blocked) {
                $validated['blocked_at'] = null;
            }
            
            // Handle password change
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }
            unset($validated['password_confirmation']);
            
            // Handle profile image
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                    \Storage::disk('public')->delete($user->profile_image);
                }
                $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
            } elseif ($request->input('remove_profile_image')) {
                if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                    \Storage::disk('public')->delete($user->profile_image);
                }
                $validated['profile_image'] = null;
            }
            unset($validated['remove_profile_image']);
            
            // Handle intro video
            if ($request->hasFile('intro_video')) {
                // Delete old video if exists
                if ($user->intro_video_path && \Storage::disk('public')->exists($user->intro_video_path)) {
                    \Storage::disk('public')->delete($user->intro_video_path);
                }
                $validated['intro_video_path'] = $request->file('intro_video')->store('intro-videos', 'public');
            } elseif ($request->input('remove_intro_video')) {
                if ($user->intro_video_path && \Storage::disk('public')->exists($user->intro_video_path)) {
                    \Storage::disk('public')->delete($user->intro_video_path);
                }
                $validated['intro_video_path'] = null;
            }
            unset($validated['remove_intro_video']);
            unset($validated['intro_video']);

            $user->update($validated);
            
            Log::info("Admin updated user: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error("Failed to update user: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user. Please try again.'
            ], 500);
        }
    }

    /**
     * Soft delete a user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting admin users
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin users cannot be deleted.'
                ], 403);
            }
            
            $user->delete();
            
            Log::info("Admin soft deleted user: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to delete user: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. Please try again.'
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted user
     */
    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            
            $user->restore();
            
            Log::info("Admin restored user: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User restored successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to restore user: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore user. Please try again.'
            ], 500);
        }
    }

    /**
     * Block a user
     */
    public function block($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin users cannot be blocked.'
                ], 403);
            }
            
            $user->update([
                'is_blocked' => true,
                'blocked_at' => now()
            ]);
            
            Log::info("Admin blocked user: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User blocked successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to block user: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to block user. Please try again.'
            ], 500);
        }
    }

    /**
     * Unblock a user
     */
    public function unblock($id)
    {
        try {
            $user = User::findOrFail($id);
            
            $user->update([
                'is_blocked' => false,
                'blocked_at' => null
            ]);
            
            Log::info("Admin unblocked user: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User unblocked successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to unblock user: User ID {$id} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to unblock user. Please try again.'
            ], 500);
        }
    }

    // Helper methods for formatting
    private function formatOccupation($value)
    {
        $map = [
            'psychologist' => 'Psychologist / Therapist / Counselor',
            'medical' => 'Medical or Healthcare Professional',
            'wellness' => 'Wellness / Fitness / Holistic Practitioner',
            'entrepreneur' => 'Entrepreneur / Business Owner',
            'finance' => 'Finance / Consulting / Marketing',
            'software' => 'Software / Product / Data Professional',
            'engineer' => 'Engineer / Technical Specialist',
            'artist' => 'Artist / Designer / Writer / Musician',
            'educator' => 'Educator / Academic / Researcher',
            'attorney' => 'Attorney / Legal / Government',
            'real_estate' => 'Real Estate / Architecture / Design',
            'hospitality' => 'Hospitality / Travel / Event Management',
            'beauty' => 'Beauty / Lifestyle / Culinary',
            'student' => 'Student',
            'parent' => 'Stay-at-Home Parent / Caregiver',
            'retired' => 'Retired / Career Transition',
            'other' => 'Other',
        ];
        return $map[$value] ?? $value;
    }

    private function formatEducation($value)
    {
        $map = [
            'less_than_bachelor' => "Less than Bachelor's degree",
            'bachelor' => "Bachelor's",
            'master' => "Master's",
            'doctorate' => 'Doctorate / Professional degree',
            'other' => 'Other',
        ];
        return $map[$value] ?? $value;
    }

    private function formatRelationshipStatus($value)
    {
        $map = [
            'single' => 'Single',
            'divorced' => 'Divorced',
            'separated' => 'Separated',
            'widowed' => 'Widowed',
            'in_a_relationship' => 'In a relationship',
            'it_is_complicated' => "It's complicated",
        ];
        return $map[$value] ?? $value;
    }

    private function formatGenderIdentity($value)
    {
        $map = [
            'women' => 'Women',
            'men' => 'Men',
            'nonbinary' => 'Nonbinary',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
        return $map[$value] ?? $value;
    }

    private function formatSexualOrientation($value)
    {
        $map = [
            'heterosexual' => 'Heterosexual',
            'lgbtq+' => 'LGBTQ+',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
        return $map[$value] ?? $value;
    }

    private function formatRelationshipType($value)
    {
        $map = [
            'serious' => 'Serious Relationship',
            'casual' => 'Casual Dating',
            'friendship' => 'Friendship',
            'open' => 'Open to Anything',
        ];
        return $map[$value] ?? $value;
    }

    private function formatDistance($value)
    {
        $map = [
            '10' => 'Within 10 miles',
            '25' => 'Within 25 miles',
            '50' => 'Within 50 miles',
            'long' => 'Open to long-distance',
        ];
        return $map[$value] ?? $value;
    }

    private function formatPhysicalActivity($value)
    {
        $map = [
            'not_active' => 'Not very active',
            'occasionally_active' => 'Occasionally active',
            'active' => 'Active',
            'fitness_lifestyle' => 'Fitness is part of my lifestyle',
        ];
        return $map[$value] ?? $value;
    }

    private function formatSubstanceUse($value)
    {
        $map = [
            'never' => 'Never',
            'occasionally' => 'Occasionally',
            'socially' => 'Socially',
            'regularly' => 'Regularly',
        ];
        return $map[$value] ?? $value;
    }

    /**
     * Normalize has_children value to enum 'yes'/'no'.
     * Handles cases where the value might be stored as 1/0, true/false, or 'yes'/'no'.
     */
    private function normalizeHasChildren($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (in_array($value, ['yes', 'Yes', '1', 1, true], true)) {
            return 'yes';
        }
        if (in_array($value, ['no', 'No', '0', 0, false], true)) {
            return 'no';
        }
        return $value;
    }

    /**
     * Format has_children for display as 'Yes' or 'No'.
     */
    private function formatHasChildren($value)
    {
        $normalized = $this->normalizeHasChildren($value);
        if ($normalized === 'yes') return 'Yes';
        if ($normalized === 'no') return 'No';
        return null;
    }
}
