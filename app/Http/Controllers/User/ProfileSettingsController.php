<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileSettingsController extends Controller
{
    /**
     * Show the profile settings page
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::guard('user')->user();
        return view('user.profile-settings', compact('user'));
    }

    /**
     * Update basic profile information
     */
    public function updateProfile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::guard('user')->user();
        
        $validator = Validator::make($request->all(), [
            // Basic Information
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'age' => 'nullable|integer|min:18|max:100',
            'bio' => 'nullable|string|max:1000',
            
            // Gender Identity & Sexual Orientation
            'gender_identity' => 'nullable|in:women,men,nonbinary,prefer_not_to_say',
            'sexual_orientation' => 'nullable|in:heterosexual,lgbtq+,prefer_not_to_say',
            'interested_in' => 'nullable|in:men,women,both',
            
            // Relationship Preferences
            'relationship_type' => 'nullable|in:serious,casual,friendship,open',
            'age_min' => 'nullable|integer|min:18|max:100',
            'age_max' => 'nullable|integer|min:18|max:100',
            
            // Relationship Context
            'relationship_status' => 'nullable|in:single,divorced,separated,widowed,in_a_relationship,it_is_complicated',
            'has_children' => 'nullable|in:yes,no',
            
            // Location
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'distance_preference' => 'nullable|in:10,25,50,long',
            
            // Lifestyle Alignment
            'occupation' => 'nullable|in:psychologist,medical,wellness,entrepreneur,finance,software,engineer,artist,educator,attorney,real_estate,hospitality,beauty,student,parent,retired,other',
            'education' => 'nullable|in:less_than_bachelor,bachelor,master,doctorate,other',
            'physical_activity' => 'nullable|in:not_active,occasionally_active,active,fitness_lifestyle',
            'substance_use' => 'nullable|string|max:255', // Legacy field
            
            // Individual Substance Use Fields
            'alcohol_use' => 'nullable|in:never,occasionally,socially,regularly',
            'cannabis_use' => 'nullable|in:never,occasionally,regularly',
            'smoking_vaping' => 'nullable|in:never,occasionally,regularly',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Update Basic Information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->bio = $request->bio;
        
        // Update Gender Identity & Sexual Orientation
        $user->gender_identity = $request->gender_identity;
        $user->sexual_orientation = $request->sexual_orientation;
        $user->interested_in = $request->interested_in;
        
        // Update Relationship Preferences
        $user->relationship_type = $request->relationship_type;
        $user->age_min = $request->age_min;
        $user->age_max = $request->age_max;
        
        // Update Relationship Context
        $user->relationship_status = $request->relationship_status;
        $user->has_children = $request->has_children;
        
        // Update Location
        $user->city = $request->city;
        $user->state = $request->state;
        $user->distance_preference = $request->distance_preference;
        
        // Update Lifestyle Alignment
        $user->occupation = $request->occupation;
        $user->education = $request->education;
        $user->physical_activity = $request->physical_activity;
        $user->substance_use = $request->substance_use; // Legacy field
        
        // Update Individual Substance Use Fields
        $user->alcohol_use = $request->alcohol_use;
        $user->cannabis_use = $request->cannabis_use;
        $user->smoking_vaping = $request->smoking_vaping;
        
        /** @phpstan-ignore-next-line */
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        /** @var User $user */
        $user = Auth::guard('user')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['current_password' => ['Current password is incorrect']]
            ]);
        }

        $user->password = Hash::make($request->new_password);
        /** @phpstan-ignore-next-line */
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
        ]);
    }

    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            /** @var User $user */
            $user = Auth::guard('user')->user();

            if ($request->hasFile('profile_image')) {
                // Delete old profile image if exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $path = $request->file('profile_image')->store('profile-images', 'public');
                
                $user->profile_image = $path;
                /** @phpstan-ignore-next-line */
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile image updated successfully!',
                    'image_url' => url('storage/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload intro video
     */
    public function uploadIntroVideo(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'intro_video' => 'required|mimes:mp4,mov,avi,wmv|max:51200', // 50MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            /** @var User $user */
            $user = Auth::guard('user')->user();

            if ($request->hasFile('intro_video')) {
                // Delete old intro video if exists
                if ($user->intro_video_path) {
                    Storage::disk('public')->delete($user->intro_video_path);
                }

                $path = $request->file('intro_video')->store('intro-videos', 'public');
                
                $user->intro_video_path = $path;
                /** @phpstan-ignore-next-line */
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Intro video uploaded successfully!',
                    'video_url' => url('storage/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No video file found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload audio prompt
     */
    public function uploadAudioPrompt(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'audio_prompt' => 'required|mimes:mp3,wav,m4a,ogg|max:10240', // 10MB max
                'prompt_title' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            /** @var User $user */
            $user = Auth::guard('user')->user();

            if ($request->hasFile('audio_prompt')) {
                $path = $request->file('audio_prompt')->store('audio-prompts', 'public');
                
                // Store audio prompt info in user's audio_prompts field (JSON)
                $audioPrompts = $user->audio_prompts ? json_decode($user->audio_prompts, true) : [];
                $audioPrompts[] = [
                    'title' => $request->prompt_title,
                    'path' => $path,
                    'uploaded_at' => now()->toISOString()
                ];

                $user->audio_prompts = json_encode($audioPrompts);
                /** @phpstan-ignore-next-line */
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Audio prompt uploaded successfully!',
                    'audio_url' => url('storage/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No audio file found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove profile image
     */
    public function removeProfileImage(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::guard('user')->user();

            if (!$user->profile_image) {
                return response()->json([
                    'success' => false,
                    'message' => 'No profile image to remove'
                ]);
            }

            // Delete the image file from storage
            Storage::disk('public')->delete($user->profile_image);

            // Clear the profile_image field in database
            $user->profile_image = null;
            /** @phpstan-ignore-next-line */
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove profile picture: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove intro video
     */
    public function removeIntroVideo(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::guard('user')->user();

            if (!$user->intro_video_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'No intro video to remove'
                ]);
            }

            // Delete the video file from storage
            Storage::disk('public')->delete($user->intro_video_path);

            // Clear the intro_video_path field in database
            $user->intro_video_path = null;
            /** @phpstan-ignore-next-line */
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Intro video removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove intro video: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirmation' => 'required|in:DELETE'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        /** @var User $user */
        $user = Auth::guard('user')->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['password' => ['Password is incorrect']]
            ]);
        }

        // Delete user files
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        if ($user->intro_video_path) {
            Storage::disk('public')->delete($user->intro_video_path);
        }

        // Delete audio prompts
        if ($user->audio_prompts) {
            $audioPrompts = json_decode($user->audio_prompts, true);
            if (is_array($audioPrompts)) {
                foreach ($audioPrompts as $prompt) {
                    if (isset($prompt['path'])) {
                        Storage::disk('public')->delete($prompt['path']);
                    }
                }
            }
        }

        // Logout and delete user
        Auth::guard('user')->logout();
        /** @phpstan-ignore-next-line */
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
            'redirect' => route('home')
        ]);
    }
}