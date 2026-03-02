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
            $table->enum('alcohol_use', ['never', 'occasionally', 'socially', 'regularly'])->nullable();
            $table->enum('cannabis_use', ['never', 'occasionally', 'regularly'])->nullable();
            $table->enum('smoking_vaping', ['never', 'occasionally', 'regularly'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alcohol_use', 'cannabis_use', 'smoking_vaping']);
        });
    }
};
