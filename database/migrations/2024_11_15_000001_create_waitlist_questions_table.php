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
        Schema::create('waitlist_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('question_type', ['text', 'textarea', 'radio', 'checkbox', 'email'])->default('text');
            $table->string('field_name')->unique();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('max_selections')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlist_questions');
    }
};
