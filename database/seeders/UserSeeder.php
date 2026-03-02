<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            [
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
                'email_verified' => 1,
            ],
            [
                'name' => 'manager',
                'username' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'manager',
                'email_verified' => 1,
            ],
            [
                'name' => 'user',
                'username' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'user',
                'email_verified' => 1,
                'active' => true,
                'age' => 28,
                'gender' => 'male',
                'city' => 'New York',
                'state' => 'NY',
                'bio' => 'Looking for meaningful connections and someone to explore the city with.',
                'interested_in' => 'women',
                'age_min' => 22,
                'age_max' => 35,
                'relationship_type' => 'serious',
                'interests' => json_encode(['hiking', 'cooking', 'travel', 'music']),
                'del_match_code' => 'FEMLC',
                'quiz_results' => json_encode([
                    'del_match_code' => 'FEMLC',
                    'is_ready' => true,
                    'all_answers' => [
                        'q1' => 'Explore and adventure',
                        'q2' => 'Deep conversations',
                        'q3' => 'Long-term relationship'
                    ]
                ]),
                'profile_image' => null, // Will use SVG placeholder
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'last_active' => now()
            ],
            // Sample female users for matching
            [
                'name' => 'Ben Carter',
                'username' => 'ben_carter',
                'email' => 'ben.carter@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified' => 1,
                'active' => true,
                'age' => 32,
                'gender' => 'female',
                'city' => 'New York',
                'state' => 'NY',
                'bio' => 'Exploring life one adventure at a time. I value freedom, honesty, and learning from new cultures. My dog is my co-pilot.',
                'interested_in' => 'men',
                'age_min' => 25,
                'age_max' => 40,
                'relationship_type' => 'serious',
                'interests' => json_encode(['travel', 'dogs', 'photography', 'hiking']),
                'del_match_code' => 'NHOAI',
                'quiz_results' => json_encode([
                    'del_match_code' => 'NHOAI',
                    'is_ready' => true,
                    'all_answers' => [
                        'q1' => 'Adventure and exploration',
                        'q2' => 'Honest communication',
                        'q3' => 'Growth and learning'
                    ]
                ]),
                'profile_image' => 'https://images.pexels.com/photos/1040880/pexels-photo-1040880.jpeg?auto=compress&cs=tinysrgb&w=800',
                'latitude' => 40.7589,
                'longitude' => -73.9851,
                'last_active' => now()
            ],
            [
                'name' => 'Jessica Rivers',
                'username' => 'jess_rivers',
                'email' => 'jessica.rivers@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified' => 1,
                'active' => true,
                'age' => 29,
                'gender' => 'female',
                'city' => 'Brooklyn',
                'state' => 'NY',
                'bio' => 'Lover of long walks, deep conversations, and finding the perfect matcha latte. Looking for a partner to build something meaningful with.',
                'interested_in' => 'men',
                'age_min' => 26,
                'age_max' => 35,
                'relationship_type' => 'serious',
                'interests' => json_encode(['coffee', 'books', 'yoga', 'art']),
                'del_match_code' => 'FEMLC',
                'quiz_results' => json_encode([
                    'del_match_code' => 'FEMLC',
                    'is_ready' => true,
                    'all_answers' => [
                        'q1' => 'Meaningful connections',
                        'q2' => 'Deep conversations',
                        'q3' => 'Long-term commitment'
                    ]
                ]),
                'profile_image' => null, // Will use SVG placeholder
                'latitude' => 40.6782,
                'longitude' => -73.9442,
                'last_active' => now()
            ],
            [
                'name' => 'Chloe Thompson',
                'username' => 'chloe_t',
                'email' => 'chloe.thompson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified' => 1,
                'active' => true,
                'age' => 27,
                'gender' => 'female',
                'city' => 'Manhattan',
                'state' => 'NY',
                'bio' => 'Curious, playful, and present. Let\'s not take things too seriously and see where the connection takes us. Big fan of live music and spontaneous road trips.',
                'interested_in' => 'men',
                'age_min' => 24,
                'age_max' => 35,
                'relationship_type' => 'casual',
                'interests' => json_encode(['music', 'travel', 'dancing', 'festivals']),
                'del_match_code' => 'UEMSE',
                'quiz_results' => json_encode([
                    'del_match_code' => 'UEMSE',
                    'is_ready' => true,
                    'all_answers' => [
                        'q1' => 'Spontaneous adventures',
                        'q2' => 'Light-hearted fun',
                        'q3' => 'Go with the flow'
                    ]
                ]),
                'profile_image' => null, // Will use SVG placeholder
                'latitude' => 40.7831,
                'longitude' => -73.9712,
                'last_active' => now()
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
