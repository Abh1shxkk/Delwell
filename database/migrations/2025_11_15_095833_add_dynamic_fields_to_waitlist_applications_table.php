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
        Schema::table('waitlist_applications', function (Blueprint $table) {
            // Add a JSON column to store all question responses dynamically
            $table->json('responses')->nullable()->after('community_values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waitlist_applications', function (Blueprint $table) {
            $table->dropColumn('responses');
        });
    }
};
