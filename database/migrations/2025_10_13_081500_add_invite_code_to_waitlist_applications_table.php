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
            $table->string('invite_code', 10)->nullable()->unique()->after('community_values');
            $table->timestamp('invite_sent_at')->nullable()->after('invite_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waitlist_applications', function (Blueprint $table) {
            $table->dropColumn(['invite_code', 'invite_sent_at']);
        });
    }
};
