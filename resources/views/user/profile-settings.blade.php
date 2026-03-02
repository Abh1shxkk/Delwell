<x-layout title="Profile Settings - DelWell">
    <div class="container mx-auto px-4 py-12" id="profile-settings-container">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-[#3A3A3A] mb-4 font-['Cormorant_Garamond']">
                <i class="fas fa-user-cog mr-3 text-[#A3B18A]"></i>Profile Settings
            </h1>
            <p class="text-xl text-gray-600">Manage your profile, media, and account preferences</p>
        </div>

        <!-- Settings Navigation -->
        <div class="content-card p-6 mb-8">
            <div class="flex flex-wrap justify-center gap-4" id="settings-tabs">
                <button class="settings-tab active" data-tab="profile">
                    <i class="fas fa-user mr-2"></i>Profile
                </button>
                <button class="settings-tab" data-tab="media">
                    <i class="fas fa-photo-video mr-2"></i>Media
                </button>
                <button class="settings-tab" data-tab="security">
                    <i class="fas fa-shield-alt mr-2"></i>Security
                </button>
                <button class="settings-tab" data-tab="account">
                    <i class="fas fa-trash-alt mr-2"></i>Account
                </button>
            </div>
        </div>

        <!-- Profile Tab -->
        <div class="tab-content active" id="profile-tab">
            <div class="content-card p-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-user text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Basic Information</h2>
                </div>
                
                <form id="profileForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#3A3A3A] mb-2">First Name </label>
                            <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required
                                       class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                                @if($user->email_verified)
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-check-circle text-green-500" title="Email Verified"></i>
                                    </div>
                                @else
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-exclamation-circle text-yellow-500" title="Email Not Verified"></i>
                                    </div>
                                @endif
                            </div>
                            @if(!$user->email_verified)
                                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Your email address is not verified.
                                        </div>
                                        <button type="button" id="resendVerificationBtn" onclick="resendEmailVerification()"
                                                class="text-sm bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded transition-colors">
                                            Resend
                                        </button>
                                    </div>
                                    <p class="text-xs text-yellow-700 mt-1">
                                        Check your inbox and click the verification link to access all features.
                                    </p>
                                </div>
                            @else
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="text-sm text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Your email address is verified!
                                        @if($user->email_verified_at)
                                            <span class="text-xs text-green-600 block mt-1">
                                                Verified on {{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="age" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Age</label>
                            <input type="number" id="age" name="age" value="{{ $user->age ?? '' }}" min="18" max="100"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                        </div>
                       
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Bio</label>
                        <textarea id="bio" name="bio" rows="4" maxlength="1000" placeholder="Tell others about yourself..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors resize-none">{{ $user->bio ?? '' }}</textarea>
                        <p class="text-sm text-gray-500 mt-2">Max 1000 characters</p>
                    </div>

                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="interested_in" class="block text-sm font-semibold text-[#3A3A3A] mb-2">  Gender Identity</label>
                           <select id="gender_identity" name="gender_identity"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    <option value="">Select Gender Identity</option>
    <option value="women" {{ ($user->gender_identity ?? '') == 'women' ? 'selected' : '' }}>Women</option>
    <option value="men" {{ ($user->gender_identity ?? '') == 'men' ? 'selected' : '' }}>Men</option>
    <option value="nonbinary" {{ ($user->gender_identity ?? '') == 'nonbinary' ? 'selected' : '' }}>Nonbinary</option>
    <option value="prefer_not_to_say" {{ ($user->gender_identity ?? '') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
</select>
                        </div>
                         <div>
                            <label for="interested_in" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Sexual Orientation</label>
                          <select id="sexual_orientation" name="sexual_orientation"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    <option value="">Select Orientation</option>
    <option value="heterosexual" {{ ($user->sexual_orientation ?? '') == 'heterosexual' ? 'selected' : '' }}>Heterosexual</option>
    <option value="lgbtq+" {{ ($user->sexual_orientation ?? '') == 'lgbtq+' ? 'selected' : '' }}>LGBTQ+</option>
    <option value="prefer_not_to_say" {{ ($user->sexual_orientation ?? '') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
</select>                        </div>

                        <div>
                            <label for="interested_in" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Interested In</label>
                            <select id="interested_in" name="interested_in"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                                <option value="">Select Preference</option>
                                <option value="men" {{ ($user->interested_in ?? '') == 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ ($user->interested_in ?? '') == 'women' ? 'selected' : '' }}>Women</option>
                                <option value="both" {{ ($user->interested_in ?? '') == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>

                        <div>
                            <label for="relationship_type" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Looking For</label>
                            <select id="relationship_type" name="relationship_type"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                                <option value="">Select type</option>
                                <option value="serious" {{ $user->relationship_type == 'serious' ? 'selected' : '' }}>Serious Relationship</option>
                                <option value="casual" {{ $user->relationship_type == 'casual' ? 'selected' : '' }}>Casual Dating</option>
                                <option value="friendship" {{ $user->relationship_type == 'friendship' ? 'selected' : '' }}>Friendship</option>
                                <option value="open" {{ $user->relationship_type == 'open' ? 'selected' : '' }}>Open to Anything</option>
                            </select>
                        </div>
                    </div>

                           <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="age_min" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Minimum Age Preference</label>
                            <input type="number" id="age_min" name="age_min" value="{{ $user->age_min ?? '' }}" min="18" max="100"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label for="age_max" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Maximum Age Preference</label>
                            <input type="number" id="age_max" name="age_max" value="{{ $user->age_max ?? '' }}" min="18" max="100"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                        </div>
                    </div>
                      <div class="flex items-center mb-6">
                    <i class="fas fa-user text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Relationship Context</h2>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="interested_in" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Current Relationship Status</label>
                            <select id="relationship_status" name="relationship_status"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    <option value="">Select Status</option>
    <option value="single" {{ ($user->relationship_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
    <option value="divorced" {{ ($user->relationship_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
    <option value="separated" {{ ($user->relationship_status ?? '') == 'separated' ? 'selected' : '' }}>Separated</option>
    <option value="widowed" {{ ($user->relationship_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
    <option value="in_a_relationship" {{ ($user->relationship_status ?? '') == 'in_a_relationship' ? 'selected' : '' }}>In a relationship</option>
    <option value="it_is_complicated" {{ ($user->relationship_status ?? '') == 'it_is_complicated' ? 'selected' : '' }}>It's complicated</option>
</select>
                        </div>
                         <div>
                            <label for="interested_in" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Do You Have Children?</label>
                            <select id="has_children" name="has_children"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    <option value="">Select Option</option>
    <option value="yes" {{ ($user->has_children ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
    <option value="no" {{ ($user->has_children ?? '') == 'no' ? 'selected' : '' }}>No</option>
</select>
                        </div>
                    </div>
                     <!-- Main Section Title -->
<div class="flex items-center mb-6">
  <i class="fas fa-user text-2xl text-[#A3B18A] mr-3"></i>
  <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">
    Lifestyle Alignment
  </h2>
</div>

<!-- Subsection (Indented & Smaller) -->
<div class="ml-6">
  <div class="flex items-center mb-4">
    <i class="fas fa-user text-lg text-[#A3B18A] mr-2"></i>
    <h6 class="text-xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">
      Location
    </h6>
  </div>

  <!-- Location Fields -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label for="city" class="block text-sm font-semibold text-[#3A3A3A] mb-2">City</label>
      <input type="text" id="city" name="city" value="{{ $user->city ?? '' }}"
             class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    </div>

    <div>
      <label for="state" class="block text-sm font-semibold text-[#3A3A3A] mb-2">State</label>
      <input type="text" id="state" name="state" value="{{ $user->state ?? '' }}"
             class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
    </div>
  </div>
</div>
<!-- Two-column layout -->
<div class="ml-6">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <!-- Distance Willing to Date -->
    <div>
      <label for="distance" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
        Distance Willing to Date
      </label>
     <select id="distance_preference" name="distance_preference"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
    <option value="" disabled {{ empty($user->distance_preference) ? 'selected' : '' }}>Select distance preference</option>
    <option value="10" {{ ($user->distance_preference ?? '') == '10' ? 'selected' : '' }}>Within 10 miles</option>
    <option value="25" {{ ($user->distance_preference ?? '') == '25' ? 'selected' : '' }}>Within 25 miles</option>
    <option value="50" {{ ($user->distance_preference ?? '') == '50' ? 'selected' : '' }}>Within 50 miles</option>
    <option value="long" {{ ($user->distance_preference ?? '') == 'long' ? 'selected' : '' }}>Open to long-distance</option>
</select>
    </div>

    <!-- Occupation / Field of Work -->
    <div>
      <label for="occupation" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
        Occupation / Field of Work
      </label>
      <select id="occupation" name="occupation"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
        <option value="" disabled {{ empty($user->occupation) ? 'selected' : '' }}>Select your field</option>
        <option value="psychologist" {{ ($user->occupation ?? '') == 'psychologist' ? 'selected' : '' }}>Psychologist / Therapist / Counselor</option>
        <option value="medical" {{ ($user->occupation ?? '') == 'medical' ? 'selected' : '' }}>Medical or Healthcare Professional</option>
        <option value="wellness" {{ ($user->occupation ?? '') == 'wellness' ? 'selected' : '' }}>Wellness / Fitness / Holistic Practitioner</option>
        <option value="entrepreneur" {{ ($user->occupation ?? '') == 'entrepreneur' ? 'selected' : '' }}>Entrepreneur / Business Owner</option>
        <option value="finance" {{ ($user->occupation ?? '') == 'finance' ? 'selected' : '' }}>Finance / Consulting / Marketing</option>
        <option value="software" {{ ($user->occupation ?? '') == 'software' ? 'selected' : '' }}>Software / Product / Data Professional</option>
        <option value="engineer" {{ ($user->occupation ?? '') == 'engineer' ? 'selected' : '' }}>Engineer / Technical Specialist</option>
        <option value="artist" {{ ($user->occupation ?? '') == 'artist' ? 'selected' : '' }}>Artist / Designer / Writer / Musician</option>
        <option value="educator" {{ ($user->occupation ?? '') == 'educator' ? 'selected' : '' }}>Educator / Academic / Researcher</option>
        <option value="attorney" {{ ($user->occupation ?? '') == 'attorney' ? 'selected' : '' }}>Attorney / Legal / Government</option>
        <option value="real_estate" {{ ($user->occupation ?? '') == 'real_estate' ? 'selected' : '' }}>Real Estate / Architecture / Design</option>
        <option value="hospitality" {{ ($user->occupation ?? '') == 'hospitality' ? 'selected' : '' }}>Hospitality / Travel / Event Management</option>
        <option value="beauty" {{ ($user->occupation ?? '') == 'beauty' ? 'selected' : '' }}>Beauty / Lifestyle / Culinary</option>
        <option value="student" {{ ($user->occupation ?? '') == 'student' ? 'selected' : '' }}>Student</option>
        <option value="parent" {{ ($user->occupation ?? '') == 'parent' ? 'selected' : '' }}>Stay-at-Home Parent / Caregiver</option>
        <option value="retired" {{ ($user->occupation ?? '') == 'retired' ? 'selected' : '' }}>Retired / Career Transition</option>
        <option value="other" {{ ($user->occupation ?? '') == 'other' ? 'selected' : '' }}>Other</option>
      </select>
    </div>


    <!-- Education Level -->
    <div>
      <label for="education" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
        Education Level
      </label>
      <select id="education" name="education"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
        <option value="" disabled {{ empty($user->education) ? 'selected' : '' }}>Select Education Level</option>
        <option value="less_than_bachelor" {{ ($user->education ?? '') == 'less_than_bachelor' ? 'selected' : '' }}>Less than Bachelor’s degree</option>
        <option value="bachelor" {{ ($user->education ?? '') == 'bachelor' ? 'selected' : '' }}>Bachelor’s</option>
        <option value="master" {{ ($user->education ?? '') == 'master' ? 'selected' : '' }}>Master’s</option>
        <option value="doctorate" {{ ($user->education ?? '') == 'doctorate' ? 'selected' : '' }}>Doctorate / Professional degree</option>
        <option value="other" {{ ($user->education ?? '') == 'other' ? 'selected' : '' }}>Other</option>
      </select>
    </div>


  </div>
</div>
<!-- New row: Physical Activity Level & Substance Use -->
<div class="ml-6 mt-6">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Physical Activity Level -->
    <div>
      <label for="physical_activity" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
        Physical Activity Level
      </label>
      <select id="physical_activity" name="physical_activity"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
        <option value="" disabled {{ empty($user->physical_activity) ? 'selected' : '' }}>Select activity level</option>
        <option value="not_active" {{ ($user->physical_activity ?? '') == 'not_active' ? 'selected' : '' }}>Not very active</option>
        <option value="occasionally_active" {{ ($user->physical_activity ?? '') == 'occasionally_active' ? 'selected' : '' }}>Occasionally active</option>
        <option value="active" {{ ($user->physical_activity ?? '') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="fitness_lifestyle" {{ ($user->physical_activity ?? '') == 'fitness_lifestyle' ? 'selected' : '' }}>Fitness is part of my lifestyle</option>
      </select>
      </div>

</div>
</div>

<!-- Substance Use Subsection -->
<div class="ml-6 mt-6">
<div class="flex items-center mb-4">
  <i class="fas fa-wine-glass text-lg text-[#A3B18A] mr-2"></i>
  <h6 class="text-xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">
    Substance Use
  </h6>
</div>

<!-- All 3 Substance Use Fields in Same Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <!-- Alcohol Use -->
  <div>
    <label for="alcohol_use" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
      Alcohol Use
    </label>
    <select id="alcohol_use" name="alcohol_use"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
      <option value="" disabled {{ empty($user->alcohol_use) ? 'selected' : '' }}>Select alcohol use</option>
      <option value="never" {{ ($user->alcohol_use ?? '') == 'never' ? 'selected' : '' }}>Never</option>
      <option value="occasionally" {{ ($user->alcohol_use ?? '') == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
      <option value="socially" {{ ($user->alcohol_use ?? '') == 'socially' ? 'selected' : '' }}>Socially</option>
      <option value="regularly" {{ ($user->alcohol_use ?? '') == 'regularly' ? 'selected' : '' }}>Regularly</option>
    </select>
  </div>

  <!-- Cannabis Use -->
  <div>
    <label for="cannabis_use" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
      Cannabis Use
    </label>
    <select id="cannabis_use" name="cannabis_use"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
      <option value="" disabled {{ empty($user->cannabis_use) ? 'selected' : '' }}>Select cannabis use</option>
      <option value="never" {{ ($user->cannabis_use ?? '') == 'never' ? 'selected' : '' }}>Never</option>
      <option value="occasionally" {{ ($user->cannabis_use ?? '') == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
      <option value="regularly" {{ ($user->cannabis_use ?? '') == 'regularly' ? 'selected' : '' }}>Regularly</option>
    </select>
  </div>

  <!-- Smoking/Vaping -->
  <div>
    <label for="smoking_vaping" class="block text-sm font-semibold text-[#3A3A3A] mb-2">
      Smoking/Vaping
    </label>
    <select id="smoking_vaping" name="smoking_vaping"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:border-[#A3B18A] focus:outline-none transition-colors">
      <option value="" disabled {{ empty($user->smoking_vaping) ? 'selected' : '' }}>Select smoking/vaping</option>
      <option value="never" {{ ($user->smoking_vaping ?? '') == 'never' ? 'selected' : '' }}>Never</option>
      <option value="occasionally" {{ ($user->smoking_vaping ?? '') == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
      <option value="regularly" {{ ($user->smoking_vaping ?? '') == 'regularly' ? 'selected' : '' }}>Regularly</option>
    </select>
  </div>
</div>
</div>
                    <div class="flex justify-end pt-6">
                        <button type="submit" class="btn-primary flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Media Tab -->
        <div class="tab-content hidden" id="media-tab">
            <!-- Profile Image Section -->
            <div class="content-card p-8 mb-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-image text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Profile Image</h2>
                </div>
                
                <div class="text-center">
                    @if($user->profile_image)
                        <div class="relative inline-block">
                            <img src="{{ url('storage/' . $user->profile_image) }}" alt="Profile" 
                                 class="w-40 h-40 rounded-full object-cover border-4 border-[#A3B18A] mx-auto mb-6">
                            <button type="button" id="removeProfileImageBtn" onclick="removeProfileImage()" 
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors shadow-lg" 
                                    title="Remove profile picture">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="w-40 h-40 rounded-full border-4 border-dashed border-gray-300 flex items-center justify-center mx-auto mb-6 bg-gray-50">
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <form id="profileImageForm">
                        <div id="profileImageDropZone" class="upload-area border-2 border-dashed border-[#A3B18A] rounded-xl p-8 cursor-pointer hover:bg-[#A3B18A]/5 transition-colors"
                             onclick="document.getElementById('profileImageInput').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-[#A3B18A] mb-4"></i>
                            <p class="text-lg font-semibold text-[#3A3A3A] mb-2">Drag & drop or click to upload new profile image</p>
                            <p class="text-sm text-gray-500">JPG, PNG, GIF (Max: 5MB)</p>
                        </div>
                        <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="hidden">
                        
                        <!-- Progress Bar -->
                        <div id="profileImageProgress" class="hidden mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-[#3A3A3A]">Uploading...</span>
                                <span id="profileImageProgressText" class="text-sm font-semibold text-[#A3B18A]">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div id="profileImageProgressBar" class="bg-[#A3B18A] h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Intro Video Section -->
            <div class="content-card p-8 mb-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-video text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Intro Video</h2>
                </div>
                
                    @if($user->intro_video_path)
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                <video controls class="max-w-md max-h-64 rounded-xl shadow-lg mx-auto">
                                    <source src="{{ url('storage/' . $user->intro_video_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" id="removeIntroVideoBtn" onclick="removeIntroVideo()" 
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors shadow-lg" 
                                        title="Remove intro video">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif                <form id="introVideoForm">
                    <div id="introVideoDropZone" class="upload-area border-2 border-dashed border-[#A3B18A] rounded-xl p-8 cursor-pointer hover:bg-[#A3B18A]/5 transition-colors text-center"
                         onclick="document.getElementById('introVideoInput').click()">
                        <i class="fas fa-video text-4xl text-[#A3B18A] mb-4"></i>
                        <p class="text-lg font-semibold text-[#3A3A3A] mb-2">
                            {{ $user->intro_video_path ? 'Drag & drop or click to replace intro video' : 'Drag & drop or click to upload intro video' }}
                        </p>
                        <p class="text-sm text-gray-500">MP4, MOV, AVI, WMV (Max: 50MB)</p>
                    </div>
                    <input type="file" id="introVideoInput" name="intro_video" accept="video/*" class="hidden">
                    
                    <!-- Progress Bar -->
                    <div id="introVideoProgress" class="hidden mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-[#3A3A3A]">Uploading...</span>
                            <span id="introVideoProgressText" class="text-sm font-semibold text-[#A3B18A]">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div id="introVideoProgressBar" class="bg-[#A3B18A] h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Audio Prompts Section -->
            <!-- <div class="content-card p-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-microphone text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Audio Prompts</h2>
                </div>
                
                @if($user->audio_prompts)
                    @php 
                        $audioPrompts = json_decode($user->audio_prompts, true);
                        $audioPrompts = is_array($audioPrompts) ? $audioPrompts : [];
                    @endphp
                    <div class="space-y-4 mb-8">
                        @foreach($audioPrompts as $prompt)
                            @if(is_array($prompt) && isset($prompt['title'], $prompt['path']))
                                <div class="bg-gray-50 border-l-4 border-[#A3B18A] rounded-lg p-4">
                                    <h4 class="font-semibold text-[#3A3A3A] mb-2">{{ $prompt['title'] }}</h4>
                                    <audio controls class="w-full mb-2">
                                        <source src="{{ url('storage/' . $prompt['path']) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <p class="text-xs text-gray-500">
                                        Uploaded: {{ isset($prompt['uploaded_at']) ? \Carbon\Carbon::parse($prompt['uploaded_at'])->format('M d, Y') : 'Unknown' }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                
                <form id="audioPromptForm" class="space-y-6">
                    <div>
                        <label for="promptTitle" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Prompt Title</label>
                        <input type="text" id="promptTitle" name="prompt_title" placeholder="e.g., My favorite memory" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                    </div>
                    <div class="upload-area border-2 border-dashed border-[#A3B18A] rounded-xl p-8 cursor-pointer hover:bg-[#A3B18A]/5 transition-colors text-center"
                         onclick="document.getElementById('audioPromptInput').click()">
                        <i class="fas fa-microphone text-4xl text-[#A3B18A] mb-4"></i>
                        <p class="text-lg font-semibold text-[#3A3A3A] mb-2">Upload audio prompt</p>
                        <p class="text-sm text-gray-500">MP3, WAV, M4A, OGG (Max: 10MB)</p>
                    </div>
                    <input type="file" id="audioPromptInput" name="audio_prompt" accept="audio/*" class="hidden">
                </form>
            </div> -->
        </div>

        <!-- Security Tab -->
        <div class="tab-content hidden" id="security-tab">
            <div class="content-card p-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-lock text-2xl text-[#A3B18A] mr-3"></i>
                    <h2 class="text-3xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">Change Password</h2>
                </div>
                
                <form id="passwordForm" class="space-y-6 max-w-md">
                    <!-- Hidden username field for accessibility and password managers -->
                    <input type="text" name="username" value="{{ $user->email ?? '' }}" autocomplete="username" style="display: none;" readonly>
                    
                    <div>
                        <label for="currentPassword" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Current Password</label>
                        <input type="password" id="currentPassword" name="current_password" autocomplete="current-password" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="newPassword" class="block text-sm font-semibold text-[#3A3A3A] mb-2">New Password</label>
                        <input type="password" id="newPassword" name="new_password" autocomplete="new-password" required minlength="8"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="confirmPassword" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="new_password_confirmation" autocomplete="new-password" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#A3B18A] focus:outline-none transition-colors">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn-primary flex items-center">
                            <i class="fas fa-key mr-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Tab -->
        <div class="tab-content hidden" id="account-tab">
            <div class="content-card p-8">
                <div class="flex items-center mb-6">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500 mr-3"></i>
                    <h2 class="text-3xl font-bold text-red-600 font-['Cormorant_Garamond']">Danger Zone</h2>
                </div>
                
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6">
                    <h3 class="text-xl font-bold text-red-600 mb-3">Delete Account</h3>
                    <p class="text-gray-700 mb-6">Once you delete your account, there is no going back. Please be certain.</p>
                    
                    <form id="deleteAccountForm" class="space-y-6 max-w-md">
                        <!-- Hidden username field for accessibility and password managers -->
                        <input type="text" name="username" value="{{ $user->email ?? '' }}" autocomplete="username" style="display: none;" readonly>
                        
                        <div>
                            <label for="deletePassword" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Enter your password to confirm</label>
                            <input type="password" id="deletePassword" name="password" autocomplete="current-password" required
                                   class="w-full px-4 py-3 border-2 border-red-200 rounded-xl focus:border-red-400 focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label for="deleteConfirmation" class="block text-sm font-semibold text-[#3A3A3A] mb-2">Type "DELETE" to confirm</label>
                            <input type="text" id="deleteConfirmation" name="confirmation" required placeholder="DELETE"
                                   class="w-full px-4 py-3 border-2 border-red-200 rounded-xl focus:border-red-400 focus:outline-none transition-colors">
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-full transition-colors flex items-center">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Account Permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Success/Error Notifications -->
    <div id="notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none; transform: translateX(350px); transition: all 0.3s ease-in-out;">
        <div id="notification-content" style="padding: 0; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); color: white; font-weight: 600; min-width: 280px; max-width: 320px; background-color: #A3B18A; overflow: hidden; font-family: 'Lato', sans-serif;">
            <!-- Main Content -->
            <div style="padding: 16px; position: relative;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; flex: 1; margin-right: 10px;">
                        <i id="notification-icon" class="fas fa-check-circle" style="margin-right: 12px; font-size: 18px; color: white; flex-shrink: 0;"></i>
                        <span id="notification-message" style="flex: 1; font-size: 13px; line-height: 1.3; word-wrap: break-word;"></span>
                    </div>
                    <button id="notification-close" style="color: white; background: rgba(255,255,255,0.2); border: none; cursor: pointer; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background-color 0.2s; flex-shrink: 0;">
                        <span style="font-size: 14px; font-weight: bold; line-height: 1;">×</span>
                    </button>
                </div>
            </div>
            <!-- Progress Bar -->
            <div style="position: relative; height: 3px; background: rgba(255,255,255,0.2); overflow: hidden;">
                <div id="notification-progress" style="position: absolute; top: 0; left: 0; height: 100%; background: rgba(255,255,255,0.7); width: 100%; transition: width linear;"></div>
            </div>
        </div>
    </div>

    <x-slot name="styles">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .settings-tab {
                padding: 0.75rem 1.5rem;
                border-radius: 9999px;
                font-weight: 600;
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                color: #6B6B6B;
                border: 2px solid #EAEAEA;
            }
            
            .settings-tab.active {
                background: #A3B18A;
                color: white;
                border-color: #A3B18A;
            }
            
            .settings-tab:hover:not(.active) {
                border-color: #A3B18A;
                color: #A3B18A;
            }
            
            .interest-option {
                cursor: pointer;
            }
            
            .interest-label {
                display: block;
                padding: 0.5rem 1rem;
                border-radius: 9999px;
                text-align: center;
                font-weight: 500;
                transition: all 0.3s ease;
                background: white;
                color: #6B6B6B;
                border: 2px solid #EAEAEA;
            }
            
            .interest-option input:checked + .interest-label {
                background: #A3B18A;
                color: white;
                border-color: #A3B18A;
            }
            
            .interest-option:hover .interest-label {
                border-color: #A3B18A;
                color: #A3B18A;
            }
            
            .interest-option input:checked:hover + .interest-label {
                color: white;
            }
        </style>
    </x-slot>

    <x-slot name="scripts">
        <script>
            // CSRF token setup
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Notification helpers (defined globally)
            function showNotification(message, type = 'success') {
                const notification = document.getElementById('notification');
                const content = document.getElementById('notification-content');
                const icon = document.getElementById('notification-icon');
                const messageEl = document.getElementById('notification-message');
                const progressBar = document.getElementById('notification-progress');
                
                if (!notification || !content || !icon || !messageEl || !progressBar) {
                    return;
                }
                
                // Update message
                messageEl.textContent = message;
                
                // Update styling based on type using DelWell colors
                if (type === 'success') {
                    content.style.backgroundColor = '#A3B18A'; // DelWell matcha green
                    icon.className = 'fas fa-check-circle';
                } else {
                    content.style.backgroundColor = '#DC2626'; // Matching red for errors
                    icon.className = 'fas fa-exclamation-circle';
                }
                
                // Reset and show progress bar
                progressBar.style.width = '100%';
                progressBar.style.transition = 'none';
                
                // Show the notification
                notification.style.display = 'block';
                notification.style.transform = 'translateX(0)';
                
                // Start progress bar animation after a small delay
                setTimeout(() => {
                    progressBar.style.transition = 'width 5s linear';
                    progressBar.style.width = '0%';
                }, 50);
                
                // Auto hide after 5 seconds (half of previous duration)
                setTimeout(() => {
                    hideNotification();
                }, 5000);
            }
            
            function hideNotification() {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.style.transform = 'translateX(350px)';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 300);
                }
            }

            // Tab switching functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.settings-tab');
                const tabContents = document.querySelectorAll('.tab-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const tabName = this.getAttribute('data-tab');
                        
                        // Remove active class from all tabs
                        tabs.forEach(t => t.classList.remove('active'));
                        // Add active class to clicked tab
                        this.classList.add('active');
                        
                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                            content.classList.remove('active');
                        });
                        
                        // Show selected tab content
                        const selectedContent = document.getElementById(tabName + '-tab');
                        if (selectedContent) {
                            selectedContent.classList.remove('hidden');
                            selectedContent.classList.add('active');
                        }
                    });
                });
            });

            // Profile form submission
            document.getElementById('profileForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("user.profile-settings.update-profile") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(Object.values(data.errors).flat().join(', '), 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });

            // Password form submission
            document.getElementById('passwordForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("user.profile-settings.update-password") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        this.reset();
                    } else {
                        showNotification(Object.values(data.errors).flat().join(', '), 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });

            // Profile image upload handler with progress
            async function uploadProfileImage(file) {
                const formData = new FormData();
                formData.append('profile_image', file);
                
                // Show progress bar
                const progressContainer = document.getElementById('profileImageProgress');
                const progressBar = document.getElementById('profileImageProgressBar');
                const progressText = document.getElementById('profileImageProgressText');
                const dropZone = document.getElementById('profileImageDropZone');
                
                progressContainer.classList.remove('hidden');
                dropZone.style.opacity = '0.5';
                dropZone.style.pointerEvents = 'none';
                
                // Use XMLHttpRequest for progress tracking
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressText.textContent = percentComplete + '%';
                    }
                });
                
                // Handle completion
                xhr.addEventListener('load', () => {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        
                        if (data.success) {
                            progressBar.style.width = '100%';
                            progressText.textContent = '100%';
                            showNotification(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(Object.values(data.errors).flat().join(', '), 'error');
                            resetProgressBar('profileImage');
                        }
                    } catch (error) {
                        showNotification('An error occurred. Please try again.', 'error');
                        resetProgressBar('profileImage');
                    }
                });
                
                // Handle errors
                xhr.addEventListener('error', () => {
                    showNotification('Upload failed. Please try again.', 'error');
                    resetProgressBar('profileImage');
                });
                
                // Send request
                xhr.open('POST', '{{ route("user.profile-settings.upload-profile-image") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send(formData);
            }
            
            function resetProgressBar(type) {
                const progressContainer = document.getElementById(type + 'Progress');
                const progressBar = document.getElementById(type + 'ProgressBar');
                const progressText = document.getElementById(type + 'ProgressText');
                const dropZone = document.getElementById(type + 'DropZone');
                
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                progressText.textContent = '0%';
                dropZone.style.opacity = '1';
                dropZone.style.pointerEvents = 'auto';
            }

            // Profile image upload - file input change
            const profileImageInput = document.getElementById('profileImageInput');
            if (profileImageInput) {
                profileImageInput.addEventListener('change', async function() {
                    if (this.files[0]) {
                        await uploadProfileImage(this.files[0]);
                    }
                });
            }

            // Profile image drag and drop
            const profileImageDropZone = document.getElementById('profileImageDropZone');
            if (profileImageDropZone) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    profileImageDropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                // Highlight drop zone when dragging over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    profileImageDropZone.addEventListener(eventName, () => {
                        profileImageDropZone.classList.add('bg-[#A3B18A]/10', 'border-[#588157]');
                        profileImageDropZone.classList.remove('border-[#A3B18A]');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    profileImageDropZone.addEventListener(eventName, () => {
                        profileImageDropZone.classList.remove('bg-[#A3B18A]/10', 'border-[#588157]');
                        profileImageDropZone.classList.add('border-[#A3B18A]');
                    });
                });

                // Handle dropped files
                profileImageDropZone.addEventListener('drop', async function(e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        // Validate file type
                        if (file.type.startsWith('image/')) {
                            await uploadProfileImage(file);
                        } else {
                            showNotification('Please upload an image file (JPG, PNG, GIF)', 'error');
                        }
                    }
                });
            }

            // Intro video upload handler with progress
            async function uploadIntroVideo(file) {
                const formData = new FormData();
                formData.append('intro_video', file);
                
                // Show progress bar
                const progressContainer = document.getElementById('introVideoProgress');
                const progressBar = document.getElementById('introVideoProgressBar');
                const progressText = document.getElementById('introVideoProgressText');
                const dropZone = document.getElementById('introVideoDropZone');
                
                progressContainer.classList.remove('hidden');
                dropZone.style.opacity = '0.5';
                dropZone.style.pointerEvents = 'none';
                
                // Use XMLHttpRequest for progress tracking
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressText.textContent = percentComplete + '%';
                    }
                });
                
                // Handle completion
                xhr.addEventListener('load', () => {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        
                        if (data.success) {
                            progressBar.style.width = '100%';
                            progressText.textContent = '100%';
                            showNotification(data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(Object.values(data.errors).flat().join(', '), 'error');
                            resetProgressBar('introVideo');
                        }
                    } catch (error) {
                        showNotification('An error occurred. Please try again.', 'error');
                        resetProgressBar('introVideo');
                    }
                });
                
                // Handle errors
                xhr.addEventListener('error', () => {
                    showNotification('Upload failed. Please try again.', 'error');
                    resetProgressBar('introVideo');
                });
                
                // Send request
                xhr.open('POST', '{{ route("user.profile-settings.upload-intro-video") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send(formData);
            }

            // Intro video upload - file input change
            const introVideoInput = document.getElementById('introVideoInput');
            if (introVideoInput) {
                introVideoInput.addEventListener('change', async function() {
                    if (this.files[0]) {
                        await uploadIntroVideo(this.files[0]);
                    }
                });
            }

            // Intro video drag and drop
            const introVideoDropZone = document.getElementById('introVideoDropZone');
            if (introVideoDropZone) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    introVideoDropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                // Highlight drop zone when dragging over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    introVideoDropZone.addEventListener(eventName, () => {
                        introVideoDropZone.classList.add('bg-[#A3B18A]/10', 'border-[#588157]');
                        introVideoDropZone.classList.remove('border-[#A3B18A]');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    introVideoDropZone.addEventListener(eventName, () => {
                        introVideoDropZone.classList.remove('bg-[#A3B18A]/10', 'border-[#588157]');
                        introVideoDropZone.classList.add('border-[#A3B18A]');
                    });
                });

                // Handle dropped files
                introVideoDropZone.addEventListener('drop', async function(e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        // Validate file type
                        if (file.type.startsWith('video/')) {
                            await uploadIntroVideo(file);
                        } else {
                            showNotification('Please upload a video file (MP4, MOV, AVI, WMV)', 'error');
                        }
                    }
                });
            }

            // Audio prompt upload
            const audioPromptInput = document.getElementById('audioPromptInput');
            if (audioPromptInput) {
                audioPromptInput.addEventListener('change', async function() {
                if (this.files[0]) {
                    const promptTitle = document.getElementById('promptTitle').value;
                    if (!promptTitle) {
                        showNotification('Please enter a prompt title first.', 'error');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('audio_prompt', this.files[0]);
                    formData.append('prompt_title', promptTitle);
                    
                    try {
                        const response = await fetch('{{ route("user.profile-settings.upload-audio-prompt") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showNotification(data.message, 'success');
                            document.getElementById('promptTitle').value = '';
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(Object.values(data.errors).flat().join(', '), 'error');
                        }
                    } catch (error) {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                }
                });
            }

            // Delete account form submission
            document.getElementById('deleteAccountForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!confirm('Are you absolutely sure? This action cannot be undone.')) {
                    return;
                }
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("user.profile-settings.delete-account") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        showNotification(Object.values(data.errors).flat().join(', '), 'error');
                    }
                } catch (error) {
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });
            
            // Close notification manually with enhanced interaction
            const closeBtn = document.getElementById('notification-close');
            closeBtn.addEventListener('click', function() {
                hideNotification();
            });
            
            // Add hover effects to close button
            closeBtn.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(255,255,255,0.3)';
            });
            
            closeBtn.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'rgba(255,255,255,0.2)';
            });

            // Remove profile image function
            window.removeProfileImage = async function() {
                if (!confirm('Are you sure you want to remove your profile picture? This action cannot be undone.')) {
                    return;
                }
                
                const btn = document.getElementById('removeProfileImageBtn');
                const originalHTML = btn.innerHTML;
                
                try {
                    // Update button state
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
                    
                    const response = await fetch('{{ route("user.profile-settings.remove-profile-image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message || 'Failed to remove profile picture. Please try again.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }
                } catch (error) {
                    showNotification('Network error. Please check your connection and try again.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            };

            // Remove intro video function
            window.removeIntroVideo = async function() {
                if (!confirm('Are you sure you want to remove your intro video? This action cannot be undone.')) {
                    return;
                }
                
                const btn = document.getElementById('removeIntroVideoBtn');
                const originalHTML = btn.innerHTML;
                
                try {
                    // Update button state
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
                    
                    const response = await fetch('{{ route("user.profile-settings.remove-intro-video") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message || 'Failed to remove intro video. Please try again.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }
                } catch (error) {
                    showNotification('Network error. Please check your connection and try again.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            };

            // Email verification resend function
            window.resendEmailVerification = async function() {
                const btn = document.getElementById('resendVerificationBtn');
                const originalText = btn.innerHTML;
                
                try {
                    // Update button state
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';
                    
                    const response = await fetch('{{ route("user.email-verification.resend") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification('Verification email sent! Please check your inbox.', 'success');
                        
                        // Start cooldown timer
                        let countdown = 60;
                        const countdownInterval = setInterval(() => {
                            btn.innerHTML = `Resend (${countdown}s)`;
                            countdown--;
                            
                            if (countdown < 0) {
                                clearInterval(countdownInterval);
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            }
                        }, 1000);
                        
                    } else {
                        showNotification(data.message || 'Failed to send verification email. Please try again.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                } catch (error) {
                    showNotification('Network error. Please check your connection and try again.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            };
            
        </script>

        <!-- Laravel Flash Messages -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification("{{ session('success') }}", 'success');
                });
            </script>
        @endif
        
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification("{{ session('error') }}", 'error');
                });
            </script>
        @endif
        
        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification("{{ $errors->first() }}", 'error');
                });
            </script>
        @endif
    </x-slot>
</x-layout>