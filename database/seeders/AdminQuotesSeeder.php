<?php

namespace Database\Seeders;

use App\Models\AdminQuote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminQuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            [
                'title' => 'DelWell Journey',
                'quote' => 'Begin your DelWell journey — a path of self-discovery that helps you connect more deeply with yourself, so you\'re open to the connections that truly align.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Journey Within',
                'quote' => 'At DelWell, the journey starts within. Discover what drives your heart before you share it with someone else.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Self-Awareness Meets Connection',
                'quote' => 'DelWell is where self-awareness meets connection — because the most meaningful relationships begin with knowing yourself.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($quotes as $quote) {
            AdminQuote::updateOrCreate(
                ['title' => $quote['title']], 
                $quote
            );
        }

        $this->command->info('Admin quotes seeded successfully!');
    }
}
