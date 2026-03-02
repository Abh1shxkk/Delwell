<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactMessage;
use App\Mail\ContactFormMail;

class CommonController extends Controller
{
    //
    public function index(){
        return view('pages.invite');
    }

    public function register(){
        return view('register');
    }

    public function showInviteCodeForm(){
        return view('pages.invite-code');
    }

    public function processInviteCode(Request $request){
        $request->validate([
            'invite_code' => 'required|string|min:6'
        ]);

        // For now, redirect to register page
        // You can add invite code validation logic here later
        return redirect()->route('register')->with('invite_code', $request->invite_code);
    }

    /**
     * Handle contact form submission
     */
    public function submitContactForm(Request $request)
    {
        try {
            // Validate the input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Store contact message in database
            $contactMessage = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'unread',
            ]);

            // Prepare email data
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'submitted_at' => now()->format('F j, Y \a\t g:i A'),
            ];

            // Send email notification to admin
            try {
                // You can configure the admin email in .env as ADMIN_EMAIL
                $adminEmail = env('ADMIN_EMAIL', 'hello@delwell.com');
                Mail::to($adminEmail)->send(new ContactFormMail($contactData));
                
                Log::info('Contact form submission received', [
                    'name' => $request->name,
                    'email' => $request->email,
                    'subject' => $request->subject,
                    'message_id' => $contactMessage->id
                ]);
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                Log::error('Failed to send contact form email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you for reaching out! We\'ll get back to you within 24 hours.'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
