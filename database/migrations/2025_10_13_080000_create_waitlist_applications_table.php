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
        Schema::create('waitlist_applications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->text('draws_you')->nullable();
            $table->string('relationship_with_self')->nullable();
            $table->json('values')->nullable(); // Store selected values as JSON
            $table->string('statement')->nullable();
            $table->string('community_values')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlist_applications');
    }
};
