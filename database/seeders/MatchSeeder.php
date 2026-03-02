<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserMatch;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $testUser = User::where('email', 'user@gmail.com')->first();
        
        if (!$testUser) {
            return;
        }
        
        // Get the sample female users
        $benCarter = User::where('email', 'ben.carter@example.com')->first();
        
        // Create a circle pick match (mutual match) with Ben Carter
        if ($benCarter) {
            // User flags Ben as circle pick
            UserMatch::updateOrCreate([
                'user_id' => $testUser->id,
                'matched_user_id' => $benCarter->id,
            ], [
                'status' => 'matched',
                'is_circle_pick' => true,
                'matched_at' => now()
            ]);
            
            // Ben also matched back (mutual)
            UserMatch::updateOrCreate([
                'user_id' => $benCarter->id,
                'matched_user_id' => $testUser->id,
            ], [
                'status' => 'matched',
                'matched_at' => now()
            ]);
        }
    }
}