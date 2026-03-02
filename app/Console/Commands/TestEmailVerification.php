<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class TestEmailVerification extends Command
{
    protected $signature = 'email:test-verification {email}';
    protected $description = 'Test email verification system with a test user';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            // Create or find a test user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => 'Test User',
                    'username' => 'testuser_' . time(),
                    'email' => $email,
                    'password' => bcrypt('password123'),
                    'role' => 'user',
                    'email_verified' => false,
                    'email_verification_token' => \Illuminate\Support\Str::random(64),
                    'status' => 0,
                    'active' => 1,
                    'accepted_terms' => true,
                ]);
                
                $this->info("Created test user: {$email}");
            } else {
                // Reset verification status for testing
                $user->update([
                    'email_verified' => false,
                    'email_verification_token' => \Illuminate\Support\Str::random(64),
                    'status' => 0,
                ]);
                
                $this->info("Reset verification for existing user: {$email}");
            }
            
            // Send verification email
            Mail::to($user->email)->send(new EmailVerification($user));
            
            $this->info("Verification email sent successfully!");
            $this->info("Verification token: {$user->email_verification_token}");
            $this->info("Verification URL: " . route('user.email-verification.verify', $user->email_verification_token));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Failed to send verification email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}