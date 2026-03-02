<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\GeminiAvatarService;

class AvatarController extends Controller
{
    /**
     * Regenerate user avatar
     */
    public function regenerate(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Validate avatar data if provided
            $avatarData = $request->input('avatar_data');
            if ($avatarData) {
                $request->validate([
                    'avatar_data.build' => 'required|string|max:100',
                    'avatar_data.skin' => 'required|string|max:100',
                    'avatar_data.hair' => 'required|string|max:100',
                    'avatar_data.eyes' => 'required|string|max:100',
                ]);
            } else {
                // Use existing avatar data
                $existingData = $user->avatar_generation_data;
                if (!$existingData || !isset($existingData['avatar_data'])) {
                    return response()->json(['error' => 'No avatar data found. Please provide avatar characteristics.'], 400);
                }
                $avatarData = $existingData['avatar_data'];
            }

            // Delete old avatar if exists
            if ($user->ai_avatar_path) {
                Storage::disk('public')->delete($user->ai_avatar_path);
            }

            // Generate new avatar
            $avatarService = new GeminiAvatarService();
            $result = $avatarService->generateAvatar(
                $avatarData,
                $user->age,
                $user->gender
            );

            if ($result['success']) {
                // Update user with new avatar
                $user->update([
                    'ai_avatar_path' => $result['avatar_path'],
                    'avatar_description' => $result['description'],
                    'avatar_generation_data' => [
                        'avatar_data' => $avatarData,
                        'age' => $user->age,
                        'gender' => $user->gender,
                        'generated_at' => now()->toISOString(),
                        'service' => 'gemini',
                        'regenerated' => true
                    ]
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Avatar regenerated successfully!',
                    'avatar_url' => asset('storage/' . $result['avatar_path']),
                    'description' => $result['description']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to generate avatar'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Avatar regeneration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Avatar regeneration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Show avatar customization modal
     */
    public function customize()
    {
        $user = Auth::guard('user')->user();
        $existingData = $user->avatar_generation_data['avatar_data'] ?? null;
        
        return view('user.avatar-customize', compact('existingData'));
    }

    /**
     * Get avatar information
     */
    public function info()
    {
        $user = Auth::guard('user')->user();
        
        return response()->json([
            'has_avatar' => !empty($user->ai_avatar_path),
            'avatar_url' => $user->ai_avatar_path ? asset('storage/' . $user->ai_avatar_path) : null,
            'description' => $user->avatar_description,
            'generation_data' => $user->avatar_generation_data
        ]);
    }
}
