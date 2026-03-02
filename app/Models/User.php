<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method bool isEmailVerificationExpired()
 * @method string generateEmailVerificationToken()
 * @method $this markEmailAsVerified()
 */
class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Basic Information
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role',
        'age',
        'bio',
        
        // Gender Identity & Sexual Orientation (yeh fields blade me hain)
        'gender_identity',        // Women, Men, Nonbinary, Prefer not to say
        'sexual_orientation',     // Heterosexual, LGBTQ+, Prefer not to say
        
        // Looking For
        'relationship_type',      // serious, casual, friendship, open
        
        // Age Preferences
        'age_min',
        'age_max',
        
        // Relationship Context
        'relationship_status',    // single, divorced, separated, widowed, in_a_relationship, it_is_complicated
        'has_children',          // yes, no
        
        // Location
        'city',
        'state',
        'latitude',
        'longitude',
        'distance_preference',   // 10, 25, 50, long (Distance Willing to Date)
        
        // Lifestyle Alignment
        'occupation',            // psychologist, medical, wellness, entrepreneur, etc.
        'education',             // less_than_bachelor, bachelor, master, doctorate, other
        'physical_activity',     // not_active, occasionally_active, active, fitness_lifestyle
        'substance_use',         // alcohol, cannabis, smoking (with frequency) - LEGACY
        
        // Individual Substance Use Fields
        'alcohol_use',           // never, occasionally, socially, regularly
        'cannabis_use',          // never, occasionally, regularly
        'smoking_vaping',        // never, occasionally, regularly
        
        // Legacy fields (purane wale)
        'gender',
        'interested_in',
        'interests',
        
        // Media & Content
        'profile_image',
        'ai_avatar_path',
        'avatar_description',
        'avatar_generation_data',
        'intro_video_path',
        'audio_prompts',
        
        // Account Settings
        'accepted_terms',
        'active',
        'is_blocked',
        'blocked_at',
        'status',
        'last_seen',
        'last_active',
        'email_notifications',
        'marketing_emails',
        'profile_completion',
        
        // Email Verification
        'email_verified',
        'email_verified_at',
        'email_verification_token',
        
        // Premium Features
        'is_premium',
        'premium_expires_at',
        
        // Privacy Settings
        'show_age',
        'show_location',
        'discovery_radius',
        
        // DelMatch
        'del_match_code',
        'quiz_results',
        'quiz_results_history',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'accepted_terms' => 'boolean',
            'active' => 'boolean',
            'email_verified' => 'boolean',
            'email_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'is_premium' => 'boolean',
            'show_age' => 'boolean',
            'show_location' => 'boolean',
            'has_children' => 'string',
            'quiz_results' => 'json',
            'quiz_results_history' => 'json',
            'avatar_generation_data' => 'json',
            'audio_prompts' => 'json',
            'last_active' => 'datetime',
            'premium_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the matches initiated by this user
     */
    public function initiatedMatches()
    {
        return $this->hasMany(UserMatch::class, 'user_id');
    }

    /**
     * Get the matches where this user was matched by others
     */
    public function receivedMatches()
    {
        return $this->hasMany(UserMatch::class, 'matched_user_id');
    }

    /**
     * Check if email verification token is expired (older than 24 hours)
     */
    public function isEmailVerificationExpired()
    {
        if (!$this->email_verification_token) {
            return false;
        }
        
        return $this->created_at->addHours(24)->isPast();
    }

    /**
     * Generate a new email verification token
     */
    public function generateEmailVerificationToken()
    {
        $this->update([
            'email_verification_token' => Str::random(64)
        ]);
        
        return $this->email_verification_token;
    }

    /**
     * Mark the user's email as verified
     */
    public function markEmailAsVerified()
    {
        $this->update([
            'email_verified' => true,
            'email_verified_at' => Carbon::now(),
            'email_verification_token' => null,
            'status' => 1,
        ]);
        
        return $this;
    }

    /**
     * Get formatted relationship status
     */
    public function getFormattedRelationshipStatusAttribute()
    {
        $statuses = [
            'single' => 'Single',
            'divorced' => 'Divorced',
            'separated' => 'Separated',
            'widowed' => 'Widowed',
            'in_a_relationship' => 'In a Relationship',
            'it_is_complicated' => "It's Complicated"
        ];
        
        return $statuses[$this->relationship_status] ?? 'Not Specified';
    }

    /**
     * Get formatted occupation
     */
    public function getFormattedOccupationAttribute()
    {
        $occupations = [
            'psychologist' => 'Psychologist / Therapist / Counselor',
            'medical' => 'Medical or Healthcare Professional',
            'wellness' => 'Wellness / Fitness / Holistic Practitioner',
            'entrepreneur' => 'Entrepreneur / Business Owner',
            'finance' => 'Finance / Consulting / Marketing',
            'software' => 'Software / Product / Data Professional',
            'engineer' => 'Engineer / Technical Specialist',
            'artist' => 'Artist / Designer / Writer / Musician',
            'educator' => 'Educator / Academic / Researcher',
            'attorney' => 'Attorney / Legal / Government',
            'real_estate' => 'Real Estate / Architecture / Design',
            'hospitality' => 'Hospitality / Travel / Event Management',
            'beauty' => 'Beauty / Lifestyle / Culinary',
            'student' => 'Student',
            'parent' => 'Stay-at-Home Parent / Caregiver',
            'retired' => 'Retired / Career Transition',
            'other' => 'Other'
        ];
        
        return $occupations[$this->occupation] ?? 'Not Specified';
    }

    /**
     * Get formatted education level
     */
    public function getFormattedEducationAttribute()
    {
        $levels = [
            'less_than_bachelor' => "Less than Bachelor's degree",
            'bachelor' => "Bachelor's",
            'master' => "Master's",
            'doctorate' => 'Doctorate / Professional degree',
            'other' => 'Other'
        ];
        
        return $levels[$this->education] ?? 'Not Specified';
    }

    /**
     * Get users in this user's circle (people who support this user)
     */
    public function circleMembers()
    {
        return $this->hasMany(CircleMember::class, 'user_id');
    }

    /**
     * Get circles this user is a member of (users this user supports)
     */
    public function memberOfCircles()
    {
        return $this->hasMany(CircleMember::class, 'member_id');
    }

    /**
     * Get invitations sent by this user
     */
    public function sentCircleInvitations()
    {
        return $this->hasMany(CircleInvitation::class, 'inviter_id');
    }
}