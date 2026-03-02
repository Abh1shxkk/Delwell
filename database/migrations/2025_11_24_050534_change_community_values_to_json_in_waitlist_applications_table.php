<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, convert existing string data to JSON format
        DB::table('waitlist_applications')->whereNotNull('community_values')->orderBy('id')->each(function ($application) {
            $value = $application->community_values;
            // If it's not already JSON, convert it to an array
            if (!is_null($value) && !json_decode($value)) {
                $jsonValue = json_encode([$value]);
                DB::table('waitlist_applications')
                    ->where('id', $application->id)
                    ->update(['community_values' => $jsonValue]);
            }
        });

        Schema::table('waitlist_applications', function (Blueprint $table) {
            // Change community_values from string to json to handle array data
            $table->json('community_values')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waitlist_applications', function (Blueprint $table) {
            // Revert community_values back to string
            $table->string('community_values')->nullable()->change();
        });
    }
};
