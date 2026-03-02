<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\QuizQuestion;

class RecalculateProfileCompletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate profile completion percentage for all users based on actual quiz questions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating profile completion for all users...');
        
        // Get total quiz questions from database
        $totalQuizQuestions = QuizQuestion::active()->count();
        $this->info("Total quiz questions in database: {$totalQuizQuestions}");
        
        $users = User::all();
        $updatedCount = 0;
        
        foreach ($users as $user) {
            $oldCompletion = $user->profile_completion;
            
            // Calculate completed quiz questions
            $completedQuizQuestions = 0;
            
            if ($user->del_match_code) {
                $completedQuizQuestions += 5; // Del Match Code questions
            }
            
            // Check if user has completed full quiz
            if ($user->quiz_results) {
                $quizResults = json_decode($user->quiz_results, true);
                if (isset($quizResults['is_ready'])) {
                    $completedQuizQuestions = $totalQuizQuestions; // Full quiz completed
                }
            }
            
            // Calculate new completion percentage
            $registrationCompletion = 30; // Assume registration is complete for existing users
            $quizCompletion = ($totalQuizQuestions > 0) ? ($completedQuizQuestions / $totalQuizQuestions) * 70 : 0;
            $newCompletion = round($registrationCompletion + $quizCompletion);
            
            // Update user
            $user->update(['profile_completion' => $newCompletion]);
            
            $this->line("User {$user->name}: {$oldCompletion}% → {$newCompletion}%");
            $updatedCount++;
        }
        
        $this->info("Successfully updated {$updatedCount} users.");
        return 0;
    }
}
