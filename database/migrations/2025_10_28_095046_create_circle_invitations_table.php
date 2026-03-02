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
        Schema::create('circle_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->string('invitee_name');
            $table->string('invitee_email');
            $table->string('relationship');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->index(['token', 'status']);
            $table->index(['inviter_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circle_invitations');
    }
};
