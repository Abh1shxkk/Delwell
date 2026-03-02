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
            // Profile information
            $table->integer('age')->nullable()->after('gender');
            $table->string('city', 100)->nullable()->after('age');
            $table->string('state', 100)->nullable()->after('city');
            $table->text('bio')->nullable()->after('state');
            
            // Dating preferences
            $table->enum('interested_in', ['men', 'women', 'both'])->nullable()->after('bio');
            $table->integer('age_min')->default(18)->after('interested_in');
            $table->integer('age_max')->default(100)->after('age_min');
            $table->enum('relationship_type', ['serious', 'casual', 'friendship', 'open'])->nullable()->after('age_max');
            $table->text('interests')->nullable()->after('relationship_type');
            
            // Notification preferences
            $table->boolean('email_notifications')->default(true)->after('interests');
            $table->boolean('marketing_emails')->default(false)->after('email_notifications');
            
            // Profile completion and verification
            $table->integer('profile_completion')->default(0)->after('marketing_emails');
            $table->string('email_verification_token')->nullable()->after('profile_completion');
            
            // Dating app specific fields
            $table->string('del_match_code', 10)->nullable()->after('email_verification_token');
            $table->json('quiz_results')->nullable()->after('del_match_code');
            $table->timestamp('last_active')->nullable()->after('quiz_results');
            $table->boolean('is_premium')->default(false)->after('last_active');
            $table->timestamp('premium_expires_at')->nullable()->after('is_premium');
            
            // Location for matching
            $table->decimal('latitude', 10, 8)->nullable()->after('premium_expires_at');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            
            // Privacy settings
            $table->boolean('show_age')->default(true)->after('longitude');
            $table->boolean('show_location')->default(true)->after('show_age');
            $table->integer('discovery_radius')->default(50)->after('show_location'); // km
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'city',
                'state',
                'bio',
                'interested_in',
                'age_min',
                'age_max',
                'relationship_type',
                'interests',
                'email_notifications',
                'marketing_emails',
                'profile_completion',
                'email_verification_token',
                'del_match_code',
                'quiz_results',
                'last_active',
                'is_premium',
                'premium_expires_at',
                'latitude',
                'longitude',
                'show_age',
                'show_location',
                'discovery_radius'
            ]);
        });
    }
};
