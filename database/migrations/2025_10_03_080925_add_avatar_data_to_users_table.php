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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ai_avatar_path')->nullable()->after('profile_image');
            $table->text('avatar_description')->nullable()->after('ai_avatar_path');
            $table->json('avatar_generation_data')->nullable()->after('avatar_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ai_avatar_path', 'avatar_description', 'avatar_generation_data']);
        });
    }
};
