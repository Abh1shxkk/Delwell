<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds a quiz_results_history JSON column to store all past quiz submissions.
     * Each entry contains the full quiz results + a submitted_at timestamp.
     * The existing quiz_results column continues to hold the latest/current results used for matching.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'quiz_results_history')) {
                $table->json('quiz_results_history')->nullable()->after('quiz_results');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('quiz_results_history');
        });
    }
};
