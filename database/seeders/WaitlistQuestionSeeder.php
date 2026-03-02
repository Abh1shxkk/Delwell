<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaitlistQuestion;

class WaitlistQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question_text' => 'What draws you to DelWell right now?',
                'question_type' => 'textarea',
                'field_name' => 'draws-you',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
                'placeholder' => 'Share what brought you here...',
                'help_text' => null,
                'options' => null,
                'max_selections' => null,
            ],
            [
                'question_text' => 'How would you describe your current relationship with yourself?',
                'question_type' => 'radio',
                'field_name' => 'relationship-with-self',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
                'placeholder' => null,
                'help_text' => null,
                'options' => [
                    'Grounded and evolving — I know who I am and what I value.',
                    'In transition — I\'m learning and unlearning.',
                    'I\'m still figuring that out.'
                ],
                'max_selections' => null,
            ],
            [
                'question_text' => 'What do you value most in a relationship?',
                'question_type' => 'checkbox',
                'field_name' => 'values',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 3,
                'placeholder' => null,
                'help_text' => 'Select up to two',
                'options' => [
                    'Emotional safety and honesty',
                    'Shared purpose and curiosity',
                    'Chemistry and playfulness',
                    'Growth and accountability'
                ],
                'max_selections' => 2,
            ],
            [
                'question_text' => 'Which statement feels most like you?',
                'question_type' => 'radio',
                'field_name' => 'statement',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 4,
                'placeholder' => null,
                'help_text' => null,
                'options' => [
                    'I\'m drawn to self-aware people who communicate openly.',
                    'I\'m exploring what that even means.',
                    'I\'m not sure I\'m ready for that level of vulnerability.'
                ],
                'max_selections' => null,
            ],
            [
                'question_text' => 'Our community values confidentiality, kindness, and authenticity. Are you willing to uphold those values?',
                'question_type' => 'radio',
                'field_name' => 'community-values',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 5,
                'placeholder' => null,
                'help_text' => null,
                'options' => [
                    'Yes — that\'s non-negotiable for me.',
                    'I\'ll do my best.',
                    'I prefer to observe rather than engage.'
                ],
                'max_selections' => null,
            ],
            [
                'question_text' => 'Your Email',
                'question_type' => 'email',
                'field_name' => 'email',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 6,
                'placeholder' => 'So we can get back to you',
                'help_text' => null,
                'options' => null,
                'max_selections' => null,
            ],
        ];

        foreach ($questions as $question) {
            WaitlistQuestion::create($question);
        }
    }
}
