<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaitlistApplication;
use Illuminate\Support\Facades\Mail;
use App\Mail\WaitlistInvite;
use Illuminate\Support\Facades\Log;

class WaitlistController extends Controller
{
    /**
     * Show waitlist management page
     */
    public function index(Request $request)
    {
        // Get statistics
        $stats = [
            'total' => WaitlistApplication::count(),
            'pending' => WaitlistApplication::where('status', 'pending')->count(),
            'approved' => WaitlistApplication::where('status', 'approved')->count(),
            'rejected' => WaitlistApplication::where('status', 'rejected')->count(),
            'invited' => WaitlistApplication::whereNotNull('invite_code')->count(),
        ];

        // If this is an AJAX request for DataTables
        if ($request->ajax() || $request->get('format') === 'json') {
            return $this->getDataTableData($request, $stats);
        }

        // Return view for regular page load
        return view('admin.waitlist.index', compact('stats'));
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
                    'total' => WaitlistApplication::count(),
                    'pending' => WaitlistApplication::where('status', 'pending')->count(),
                    'approved' => WaitlistApplication::where('status', 'approved')->count(),
                    'rejected' => WaitlistApplication::where('status', 'rejected')->count(),
                    'invited' => WaitlistApplication::whereNotNull('invite_code')->count(),
                ]
            ]);
        }

        $query = WaitlistApplication::query();

        // Handle DataTables search
        if ($request->has('search') && !empty($request->get('search')['value'])) {
            $searchValue = $request->get('search')['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('email', 'like', "%{$searchValue}%")
                  ->orWhere('status', 'like', "%{$searchValue}%")
                  ->orWhere('invite_code', 'like', "%{$searchValue}%");
            });
        }

        // Handle column ordering
        if ($request->has('order')) {
            $orderColumn = $request->get('order')[0]['column'];
            $orderDirection = $request->get('order')[0]['dir'];
            
            $columns = ['id', 'email', 'status', 'created_at', 'invite_code', 'invite_sent_at', 'actions'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get total records count
        $totalRecords = WaitlistApplication::count();
        $filteredRecords = $query->count();

        // Handle pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        $applications = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $data = $applications->map(function ($application) {
            return [
                'id' => $application->id,
                'email' => $application->email,
                'status' => $application->status,
                'created_at' => $application->created_at->toISOString(),
                'invite_code' => $application->invite_code,
                'invite_sent_at' => $application->invite_sent_at ? $application->invite_sent_at->toISOString() : null,
                'draws_you' => $application->draws_you,
                'relationship_with_self' => $application->relationship_with_self,
                'values' => $application->values,
                'statement' => $application->statement,
                'community_values' => $application->community_values,
                'responses' => $application->responses,
                'actions' => $application->id
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
     * Approve application and send invite code
     */
    public function approve(Request $request, $applicationId)
    {
        try {
            $application = WaitlistApplication::findOrFail($applicationId);
            
            if ($application->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Application is already approved.'
                ]);
            }
            
            // Update status and generate invite code
            $application->update([
                'status' => 'approved',
                'reviewed_at' => now()
            ]);
            
            $inviteCode = $application->generateInviteCode();
            
            // Send invitation email
            try {
                Mail::to($application->email)->send(new WaitlistInvite($application));
                $application->update(['invite_sent_at' => now()]);
            } catch (\Exception $mailException) {
                Log::error("Mail sending failed for {$application->email}: " . $mailException->getMessage());
                // Don't fail the approval, just log the error
                return response()->json([
                    'success' => true,
                    'message' => 'Application approved but email failed to send. Use "Resend Invite" button.',
                    'invite_code' => $inviteCode,
                    'email_failed' => true
                ]);
            }
            
            Log::info("Admin approved waitlist application and sent invite: {$application->email} (Code: {$inviteCode})");
            
            return response()->json([
                'success' => true,
                'message' => 'Application approved and invitation sent successfully.',
                'invite_code' => $inviteCode
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin failed to approve waitlist application: ID {$applicationId} - {$e->getMessage()}");
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject application
     */
    public function reject(Request $request, $applicationId)
    {
        try {
            $application = WaitlistApplication::findOrFail($applicationId);
            
            $application->update([
                'status' => 'rejected',
                'reviewed_at' => now()
            ]);
            
            Log::info("Admin rejected waitlist application: {$application->email}");
            
            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Admin failed to reject waitlist application: ID {$applicationId} - {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject application. Please try again.'
            ], 500);
        }
    }

    /**
     * Resend invitation email
     */
    public function resendInvite(Request $request, $applicationId)
    {
        try {
            $application = WaitlistApplication::findOrFail($applicationId);
            
            if ($application->status !== 'approved' || !$application->invite_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application must be approved with an invite code to resend.'
                ]);
            }
            
            // Check if email configuration is set
            if (!config('mail.mailers.smtp.host')) {
                Log::error('Mail configuration not set properly');
                return response()->json([
                    'success' => false,
                    'message' => 'Email service is not configured. Please contact administrator.'
                ], 500);
            }
            
            // Send invitation email
            try {
                Mail::to($application->email)->send(new WaitlistInvite($application));
            } catch (\Exception $mailException) {
                Log::error("Mail sending failed for {$application->email}: " . $mailException->getMessage());
                throw new \Exception("Failed to send email: " . $mailException->getMessage());
            }
            
            $application->update(['invite_sent_at' => now()]);
            
            Log::info("Admin resent waitlist invitation: {$application->email} (Code: {$application->invite_code})");
            
            return response()->json([
                'success' => true,
                'message' => 'Invitation resent successfully to ' . $application->email
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Application not found: ID {$applicationId}");
            return response()->json([
                'success' => false,
                'message' => 'Application not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error("Admin failed to resend waitlist invitation: ID {$applicationId} - {$e->getMessage()}");
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend invitation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve applications
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:waitlist_applications,id'
        ]);
        
        $applicationIds = $request->input('application_ids');
        $successCount = 0;
        $errorCount = 0;
        $alreadyApproved = 0;
        
        foreach ($applicationIds as $applicationId) {
            try {
                $application = WaitlistApplication::find($applicationId);
                
                if ($application->status === 'approved') {
                    $alreadyApproved++;
                    continue;
                }
                
                $application->update([
                    'status' => 'approved',
                    'reviewed_at' => now()
                ]);
                
                $inviteCode = $application->generateInviteCode();
                Mail::to($application->email)->send(new WaitlistInvite($application));
                $application->update(['invite_sent_at' => now()]);
                
                $successCount++;
                
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Bulk approve failed for application ID {$applicationId}: " . $e->getMessage());
            }
        }
        
        $message = "Bulk approval completed. ";
        $message .= "Approved: {$successCount}, ";
        $message .= "Already approved: {$alreadyApproved}, ";
        $message .= "Errors: {$errorCount}";
        
        Log::info("Admin bulk approved waitlist applications. " . $message);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'stats' => [
                'approved' => $successCount,
                'already_approved' => $alreadyApproved,
                'errors' => $errorCount
            ]
        ]);
    }
}
