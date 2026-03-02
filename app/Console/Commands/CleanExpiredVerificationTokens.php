<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CleanExpiredVerificationTokens extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:clean-expired-tokens {--dry-run : Show what would be cleaned without actually doing it}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up expired email verification tokens (older than 24 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Cleaning expired email verification tokens...');
        
        // Find users with expired verification tokens
        $expiredUsers = User::whereNotNull('email_verification_token')
            ->where('email_verified', false)
            ->where('created_at', '<', now()->subHours(24))
            ->get();
        
        if ($expiredUsers->isEmpty()) {
            $this->info('No expired verification tokens found.');
            return 0;
        }
        
        $this->info("Found {$expiredUsers->count()} users with expired verification tokens.");
        
        if ($dryRun) {
            $this->warn('DRY RUN - No changes will be made');
            
            $this->table(
                ['ID', 'Email', 'Created At', 'Days Expired'],
                $expiredUsers->map(function ($user) {
                    return [
                        $user->id,
                        $user->email,
                        $user->created_at->format('Y-m-d H:i:s'),
                        $user->created_at->diffInDays(now()) - 1 // Subtract 1 since expired after 24 hours
                    ];
                })
            );
            
            return 0;
        }
        
        // Ask for confirmation
        if (!$this->confirm('Do you want to clean these expired tokens?')) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        $cleanedCount = 0;
        $errorCount = 0;
        
        foreach ($expiredUsers as $user) {
            try {
                // Clear the expired token
                $user->update(['email_verification_token' => null]);
                $cleanedCount++;
                
                $this->line("✓ Cleaned token for: {$user->email}");
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Failed to clean token for: {$user->email} - {$e->getMessage()}");
                Log::error("Failed to clean expired verification token for user {$user->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\nCleaning completed:");
        $this->info("- Successfully cleaned: {$cleanedCount}");
        
        if ($errorCount > 0) {
            $this->error("- Errors: {$errorCount}");
        }
        
        Log::info("Email verification token cleanup completed. Cleaned: {$cleanedCount}, Errors: {$errorCount}");
        
        return 0;
    }
}