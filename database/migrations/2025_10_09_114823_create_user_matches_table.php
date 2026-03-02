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
        Schema::create('user_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('matched_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'matched', 'rejected'])->default('pending');
            $table->boolean('is_circle_pick')->default(false);
            $table->timestamp('matched_at')->nullable();
            $table->timestamps();
            
            // Ensure a user can only have one match record with another user
            $table->unique(['user_id', 'matched_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_matches');
    }
};
