<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Show email verification management page
     */
    public function index(Request $request)
    {
        // Get statistics
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'verified' => User::where('role', 'user')->where('email_verified', true)->count(),
            'unverified' => User::where('role', 'user')->where('email_verified', false)->count(),
            'expired' => User::where('role', 'user')
                           ->where('email_verified', false)
                           ->whereNotNull('email_verification_token')
                           ->where('created_at', '<', now()->subHours(24))
                           ->count(),
        ];

        // If this is an AJAX request for DataTables
        if ($request->ajax() || $request->get('format') === 'json') {
            return $this->getDataTableData($request, $stats);
        }

        // Return view for regular page load
        return view('admin.email-verification.index', compact('stats'));
    }

    /**
     * Get data for DataTables
     */
    private function getDataTableData(Request $request, $stats = null)
    {
        // Return just stats if requested
        if ($request->get('stats_only')) {
            return response()->json([
                'stats' => $stats ?: [
                    'total' => User::where('role', 'user')->count(),
                    'verified' => User::where('role', 'user')->where('email_verified', true)->count(),
                    'unverified' => User::where('role', 'user')->where('email_verified', false)->count(),
                    'expired' => User::where('role', 'user')
                               ->where('email_verified', false)
                               ->whereNotNull('email_verification_token')
                               ->where('created_at', '<', now()->subHours(24))
                               ->count(),
                ]
            ]);
        }

        $query = User::where('role', 'user');

        // Handle DataTables search
        if ($request->has('search') && !empty($request->get('search')['value'])) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('username', 'like', "%{$searchValue}%");
            });
        }

        // Handle column ordering
        if ($request->has('order')) {
            $orderColumn = $request->get('order')[0]['column'];
            $orderDirection = $request->get('order')[0]['dir'];
            
            $columns = ['id', 'name', 'email', 'email_verified', 'created_at', 'email_verification_token', 'actions'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get total records count
        $totalRecords = User::where('role', 'user')->count();
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
                'email_verified' => $user->email_verified,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->toISOString(),
                'email_verification_token' => $user->email_verification_token,
                'profile_image' => $user->profile_image,
                'ai_avatar_path' => $user->ai_avatar_path,
                'user_info' => $user->name, // Used for searching
                'verification_status' => $user->email_verified ? 'verified' : 'unverified',
                'token_status' => $user->email_verification_token ? 
                    ($user->created_at->addHours(24)->isPast() ? 'expired' : 'active') : 'none',
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
     * Manually verify a user's email
     */
    public function verifyUser(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            if ($user->email_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'User email is already verified.'
                ]);
            }
            
            $user->markEmailAsVerified();
            
            Log::info("Admin manually verified user email: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'User email verified successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin failed to verify user email: User ID {$userId} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify user email. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Send/resend verification email to a user
     */
    public function resendVerification(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            if ($user->email_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'User email is already verified.'
                ]);
            }
            
            // Generate new token if needed
            if (!$user->email_verification_token || $user->isEmailVerificationExpired()) {
                $user->generateEmailVerificationToken();
            }
            
            // Send verification email
            Mail::to($user->email)->send(new EmailVerification($user));
            
            Log::info("Admin resent verification email to: {$user->email} (User ID: {$user->id})");
            
            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin failed to resend verification email: User ID {$userId} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Bulk verify users
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        $userIds = $request->input('user_ids');
        $successCount = 0;
        $errorCount = 0;
        $alreadyVerified = 0;
        
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                
                if ($user->email_verified) {
                    $alreadyVerified++;
                    continue;
                }
                
                $user->markEmailAsVerified();
                $successCount++;
                
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Bulk verify failed for user ID {$userId}: " . $e->getMessage());
            }
        }
        
        $message = "Bulk verification completed. ";
        $message .= "Verified: {$successCount}, ";
        $message .= "Already verified: {$alreadyVerified}, ";
        $message .= "Errors: {$errorCount}";
        
        Log::info("Admin bulk verified users. " . $message);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'stats' => [
                'verified' => $successCount,
                'already_verified' => $alreadyVerified,
                'errors' => $errorCount
            ]
        ]);
    }
    
    /**
     * Bulk resend verification emails
     */
    public function bulkResend(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        $userIds = $request->input('user_ids');
        $successCount = 0;
        $errorCount = 0;
        $alreadyVerified = 0;
        
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                
                if ($user->email_verified) {
                    $alreadyVerified++;
                    continue;
                }
                
                // Generate new token if needed
                if (!$user->email_verification_token || $user->isEmailVerificationExpired()) {
                    $user->generateEmailVerificationToken();
                }
                
                // Send verification email
                Mail::to($user->email)->send(new EmailVerification($user));
                $successCount++;
                
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Bulk resend failed for user ID {$userId}: " . $e->getMessage());
            }
        }
        
        $message = "Bulk resend completed. ";
        $message .= "Sent: {$successCount}, ";
        $message .= "Already verified: {$alreadyVerified}, ";
        $message .= "Errors: {$errorCount}";
        
        Log::info("Admin bulk resent verification emails. " . $message);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'stats' => [
                'sent' => $successCount,
                'already_verified' => $alreadyVerified,
                'errors' => $errorCount
            ]
        ]);
    }
    
    /**
     * Clean expired verification tokens
     */
    public function cleanExpiredTokens()
    {
        try {
            $allUsers = User::whereNotNull('email_verification_token')
                ->where('email_verified', false)
                ->get();
            
            $cleanedCount = 0;
            
            foreach ($allUsers as $user) {
                // Use the same expiration logic as the User model
                if ($user->isEmailVerificationExpired()) {
                    $user->update(['email_verification_token' => null]);
                    $cleanedCount++;
                }
            }
            
            Log::info("Admin cleaned {$cleanedCount} expired verification tokens");
            
            return response()->json([
                'success' => true,
                'message' => "Successfully cleaned {$cleanedCount} expired verification tokens."
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin failed to clean expired tokens: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clean expired tokens. Please try again.'
            ], 500);
        }
    }
}