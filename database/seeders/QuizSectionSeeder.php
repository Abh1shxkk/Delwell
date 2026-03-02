<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuizSection;

class QuizSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Del Match Code™',
                'description' => 'Core compatibility assessment based on DelWell\'s proprietary matching algorithm',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Attachment Style',
                'description' => 'Understanding how you connect and bond in relationships',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Family Imprint',
                'description' => 'How your family background influences your relationship patterns',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Energy Style',
                'description' => 'Your natural energy patterns and how they affect compatibility',
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Practical Compatibility',
                'description' => 'Day-to-day lifestyle and practical considerations for long-term compatibility',
                'order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Communication Style',
                'description' => 'How you express yourself and process information in relationships',
                'order' => 6,
                'is_active' => true
            ],
            [
                'name' => 'Values & Beliefs',
                'description' => 'Core values, beliefs, and life philosophy alignment',
                'order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'Emotional Intelligence',
                'description' => 'Understanding and managing emotions in relationship contexts',
                'order' => 8,
                'is_active' => true
            ]
        ];

        foreach ($sections as $section) {
            QuizSection::create($section);
        }
    }
}
