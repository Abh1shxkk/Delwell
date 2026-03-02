<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Send a test email to verify configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            Mail::raw('This is a test email from DelWell to verify your email configuration is working correctly.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('DelWell - Email Configuration Test');
            });
            
            $this->info("Test email sent successfully to: {$email}");
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Failed to send test email: " . $e->getMessage());
            return 1;
        }
    }
}