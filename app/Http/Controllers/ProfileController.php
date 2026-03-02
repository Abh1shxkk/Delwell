<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show profile completion page
     */
    public function index()
    {
        return view('profile-completion');
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            
            // Delete old profile image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // Store new image
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            
            // Update user
            $user->update([
                'profile_image' => $imagePath
            ]);
            
            // Update profile completion
            $this->updateProfileCompletion($user);
            
            return redirect()->back()->with('success', 'Profile photo updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to upload photo. Please try again.']);
        }
    }

    /**
     * Update bio
     */
    public function updateBio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->update([
                'bio' => $request->bio
            ]);
            
            // Update profile completion
            $this->updateProfileCompletion($user);
            
            return redirect()->back()->with('success', 'Bio updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update bio. Please try again.']);
        }
    }

    /**
     * Update interests
     */
    public function updateInterests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interests' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->update([
                'interests' => $request->interests
            ]);
            
            // Update profile completion
            $this->updateProfileCompletion($user);
            
            return redirect()->back()->with('success', 'Interests updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update interests. Please try again.']);
        }
    }

    /**
     * Update dating preferences
     */
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interested_in' => 'required|in:men,women,both',
            'age_min' => 'required|integer|min:18|max:100',
            'age_max' => 'required|integer|min:18|max:100|gte:age_min',
            'relationship_type' => 'nullable|in:serious,casual,friendship,open',
            'discovery_radius' => 'required|integer|min:5|max:200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $user->update([
                'interested_in' => $request->interested_in,
                'age_min' => $request->age_min,
                'age_max' => $request->age_max,
                'relationship_type' => $request->relationship_type,
                'discovery_radius' => $request->discovery_radius,
            ]);
            
            // Update profile completion
            $this->updateProfileCompletion($user);
            
            return redirect()->back()->with('success', 'Dating preferences updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update preferences. Please try again.']);
        }
    }

    /**
     * Calculate and update profile completion percentage
     */
    protected function updateProfileCompletion(User $user)
    {
        $totalFields = 15;
        $completedFields = 7; // Required fields from registration
        
        // Additional optional fields
        if ($user->profile_image) $completedFields++;
        if ($user->phone) $completedFields++;
        if ($user->state) $completedFields++;
        if ($user->bio) $completedFields++;
        if ($user->interests) $completedFields++;
        if ($user->relationship_type) $completedFields++;
        if ($user->del_match_code) $completedFields += 2; // Quiz completion is worth more
        
        $completion = round(($completedFields / $totalFields) * 100);
        
        $user->update(['profile_completion' => $completion]);
        
        return $completion;
    }
}
