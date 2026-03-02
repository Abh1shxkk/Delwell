<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_id')->unique(); // e.g., 'q1', 'q2'
            $table->string('section'); // e.g., 'Del Match Code™', 'Attachment Style'
            $table->text('question'); // The actual question text
            $table->string('type')->default('single'); // single, multiple (for future use)
            $table->json('options'); // Array of options with text and value
            $table->integer('order')->default(0); // For ordering questions
            $table->boolean('is_active')->default(true); // To enable/disable questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
