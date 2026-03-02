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
        Schema::create('circle_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Main user
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade'); // Circle member
            $table->string('relationship'); // Best Friend, Family, etc.
            $table->foreignId('invitation_id')->nullable()->constrained('circle_invitations')->onDelete('set null');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            // Prevent duplicate relationships
            $table->unique(['user_id', 'member_id']);
            $table->index(['user_id', 'joined_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circle_members');
    }
};
