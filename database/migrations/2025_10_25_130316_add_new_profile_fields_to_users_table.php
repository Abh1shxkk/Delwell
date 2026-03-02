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
            // Basic Profile Fields (agar nahi hain toh add karenge)
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('age');
            }
            
            // Gender Identity & Sexual Orientation (NEW from UI)
            if (!Schema::hasColumn('users', 'gender_identity')) {
                $table->enum('gender_identity', ['women', 'men', 'nonbinary', 'prefer_not_to_say'])
                    ->nullable()
                    ->after('gender');
            }
            
            if (!Schema::hasColumn('users', 'sexual_orientation')) {
                $table->enum('sexual_orientation', ['heterosexual', 'lgbtq+', 'prefer_not_to_say'])
                    ->nullable()
                    ->after('gender_identity');
            }
            
            // Relationship Type (Looking For)
            if (!Schema::hasColumn('users', 'relationship_type')) {
                $table->enum('relationship_type', ['serious', 'casual', 'friendship', 'open'])
                    ->nullable()
                    ->after('sexual_orientation');
            }
            
            // Age Preferences
            if (!Schema::hasColumn('users', 'age_min')) {
                $table->integer('age_min')->nullable()->after('relationship_type');
            }
            
            if (!Schema::hasColumn('users', 'age_max')) {
                $table->integer('age_max')->nullable()->after('age_min');
            }
            
            // Relationship Context (NEW from UI)
            if (!Schema::hasColumn('users', 'relationship_status')) {
                $table->enum('relationship_status', [
                    'single', 
                    'divorced', 
                    'separated', 
                    'widowed', 
                    'in_a_relationship', 
                    'it_is_complicated'
                ])->nullable()->after('age_max');
            }
            
            if (!Schema::hasColumn('users', 'has_children')) {
                $table->enum('has_children', ['yes', 'no'])
                    ->nullable()
                    ->after('relationship_status');
            }
            
            // Location Fields
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('has_children');
            }
            
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('state');
            }
            
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            // Distance Preference (NEW from UI)
            if (!Schema::hasColumn('users', 'distance_preference')) {
                $table->enum('distance_preference', ['10', '25', '50', 'long'])
                    ->nullable()
                    ->after('longitude');
            }
            
            // Occupation (NEW from UI)
            if (!Schema::hasColumn('users', 'occupation')) {
                $table->enum('occupation', [
                    'psychologist',
                    'medical',
                    'wellness',
                    'entrepreneur',
                    'finance',
                    'software',
                    'engineer',
                    'artist',
                    'educator',
                    'attorney',
                    'real_estate',
                    'hospitality',
                    'beauty',
                    'student',
                    'parent',
                    'retired',
                    'other'
                ])->nullable()->after('distance_preference');
            }
            
            // Education Level (NEW from UI)
            if (!Schema::hasColumn('users', 'education')) {
                $table->enum('education', [
                    'less_than_bachelor',
                    'bachelor',
                    'master',
                    'doctorate',
                    'other'
                ])->nullable()->after('occupation');
            }
            
            // Physical Activity Level (NEW from UI)
            if (!Schema::hasColumn('users', 'physical_activity')) {
                $table->enum('physical_activity', [
                    'not_active',
                    'occasionally_active',
                    'active',
                    'fitness_lifestyle'
                ])->nullable()->after('education');
            }
            
            // Substance Use (NEW from UI)
            if (!Schema::hasColumn('users', 'substance_use')) {
                $table->string('substance_use')->nullable()->after('physical_activity');
            }
            
            // Media Fields
            if (!Schema::hasColumn('users', 'intro_video_path')) {
                $table->string('intro_video_path')->nullable()->after('profile_image');
            }
            
            if (!Schema::hasColumn('users', 'audio_prompts')) {
                $table->json('audio_prompts')->nullable()->after('intro_video_path');
            }
            
            if (!Schema::hasColumn('users', 'ai_avatar_path')) {
                $table->string('ai_avatar_path')->nullable()->after('audio_prompts');
            }
            
            if (!Schema::hasColumn('users', 'avatar_description')) {
                $table->text('avatar_description')->nullable()->after('ai_avatar_path');
            }
            
            if (!Schema::hasColumn('users', 'avatar_generation_data')) {
                $table->json('avatar_generation_data')->nullable()->after('avatar_description');
            }
            
            // Email Verification Token
            if (!Schema::hasColumn('users', 'email_verification_token')) {
                $table->string('email_verification_token')->nullable()->after('email_verified_at');
            }
            
            // Other Important Fields
            if (!Schema::hasColumn('users', 'interested_in')) {
                $table->string('interested_in')->nullable()->after('bio');
            }
            
            if (!Schema::hasColumn('users', 'interests')) {
                $table->json('interests')->nullable()->after('interested_in');
            }
            
            if (!Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true)->after('interests');
            }
            
            if (!Schema::hasColumn('users', 'marketing_emails')) {
                $table->boolean('marketing_emails')->default(false)->after('email_notifications');
            }
            
            if (!Schema::hasColumn('users', 'profile_completion')) {
                $table->integer('profile_completion')->default(0)->after('marketing_emails');
            }
            
            if (!Schema::hasColumn('users', 'del_match_code')) {
                $table->string('del_match_code')->nullable()->unique()->after('profile_completion');
            }
            
            if (!Schema::hasColumn('users', 'quiz_results')) {
                $table->json('quiz_results')->nullable()->after('del_match_code');
            }
            
            if (!Schema::hasColumn('users', 'last_active')) {
                $table->timestamp('last_active')->nullable()->after('last_seen');
            }
            
            if (!Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('quiz_results');
            }
            
            if (!Schema::hasColumn('users', 'premium_expires_at')) {
                $table->timestamp('premium_expires_at')->nullable()->after('is_premium');
            }
            
            if (!Schema::hasColumn('users', 'show_age')) {
                $table->boolean('show_age')->default(true)->after('premium_expires_at');
            }
            
            if (!Schema::hasColumn('users', 'show_location')) {
                $table->boolean('show_location')->default(true)->after('show_age');
            }
            
            if (!Schema::hasColumn('users', 'discovery_radius')) {
                $table->integer('discovery_radius')->default(50)->after('show_location');
            }
        });
        
        // Add indexes for better performance
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', ['email_verified'])) {
                $table->index('email_verified');
            }
            if (!Schema::hasIndex('users', ['is_premium'])) {
                $table->index('is_premium');
            }
            if (!Schema::hasIndex('users', ['del_match_code'])) {
                $table->index('del_match_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove all new columns
            $columns = [
                'age', 'bio', 'gender_identity', 'sexual_orientation',
                'relationship_type', 'age_min', 'age_max',
                'relationship_status', 'has_children',
                'city', 'state', 'latitude', 'longitude',
                'distance_preference', 'occupation', 'education',
                'physical_activity', 'substance_use',
                'intro_video_path', 'audio_prompts',
                'ai_avatar_path', 'avatar_description', 'avatar_generation_data',
                'email_verification_token', 'interested_in', 'interests',
                'email_notifications', 'marketing_emails', 'profile_completion',
                'del_match_code', 'quiz_results', 'last_active',
                'is_premium', 'premium_expires_at',
                'show_age', 'show_location', 'discovery_radius'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};