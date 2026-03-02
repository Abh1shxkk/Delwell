<x-layout title="DelWell - Lifestyle & Wellness">
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Important Notice Banner -->
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-blue-800 mb-1">Complete Your Profile to Continue</h3>
                    <p class="text-sm text-blue-700">Please complete all profile settings and deepen profile questions to access your dashboard and discover matches. All fields marked with <span class="text-red-500">*</span> are required.</p>
                </div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Profile Completion</span>
                <span class="text-sm font-medium text-purple-600">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span class="font-semibold text-purple-600">Step 3 of 4</span>
                <span>Lifestyle & Wellness</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Your Lifestyle</h2>
                <p class="text-gray-600">Share your lifestyle preferences for better compatibility</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.onboarding.save-step3') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Occupation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Occupation <span class="text-red-500">*</span>
                    </label>
                    <select name="occupation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select occupation...</option>
                        @php
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
                        @endphp
                        @foreach($occupations as $value => $label)
                        <option value="{{ $value }}" {{ old('occupation', $user->occupation) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Education -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Education Level <span class="text-red-500">*</span>
                    </label>
                    <select name="education" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select education level...</option>
                        @foreach(['less_than_bachelor' => "Less than Bachelor's degree", 'bachelor' => "Bachelor's", 'master' => "Master's", 'doctorate' => 'Doctorate / Professional degree', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" {{ old('education', $user->education) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Physical Activity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Physical Activity Level <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['not_active' => 'Not Active', 'occasionally_active' => 'Occasionally Active', 'active' => 'Active', 'fitness_lifestyle' => 'Fitness Lifestyle'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('physical_activity', $user->physical_activity) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="physical_activity" value="{{ $value }}" {{ old('physical_activity', $user->physical_activity) == $value ? 'checked' : '' }} required class="hidden">
                            <span class="flex-1 text-center font-medium text-sm">{{ $label }}</span>
                            <span class="checkmark absolute top-2 right-2 hidden">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Substance Use -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Substance Use</h3>
                    
                    <!-- Alcohol -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alcohol Use <span class="text-red-500">*</span>
                        </label>
                        <select name="alcohol_use" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select...</option>
                            @foreach(['never' => 'Never', 'occasionally' => 'Occasionally', 'socially' => 'Socially', 'regularly' => 'Regularly'] as $value => $label)
                            <option value="{{ $value }}" {{ old('alcohol_use', $user->alcohol_use) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cannabis -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cannabis Use <span class="text-red-500">*</span>
                        </label>
                        <select name="cannabis_use" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select...</option>
                            @foreach(['never' => 'Never', 'occasionally' => 'Occasionally', 'regularly' => 'Regularly'] as $value => $label)
                            <option value="{{ $value }}" {{ old('cannabis_use', $user->cannabis_use) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Smoking/Vaping -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Smoking/Vaping <span class="text-red-500">*</span>
                        </label>
                        <select name="smoking_vaping" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select...</option>
                            @foreach(['never' => 'Never', 'occasionally' => 'Occasionally', 'regularly' => 'Regularly'] as $value => $label)
                            <option value="{{ $value }}" {{ old('smoking_vaping', $user->smoking_vaping) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-6 border-t-2 border-gray-200">
                    <a href="{{ route('user.onboarding.step2') }}" 
                       class="w-full sm:flex-1 bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg hover:bg-gray-300 transition-all duration-200 text-center inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Back</span>
                    </a>
                    <button type="submit" 
                            class="w-full sm:flex-1 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center"
                            style="background: linear-gradient(to right, #9333ea, #ec4899);">
                        <span>Continue to Step 4</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.radio-option.selected {
    border-color: #9333ea !important;
    background-color: #f3e8ff !important;
}

.radio-option.selected .checkmark {
    display: block !important;
}

.radio-option:hover {
    border-color: #c084fc;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle radio button clicks
    const radioOptions = document.querySelectorAll('.radio-option');
    
    radioOptions.forEach(option => {
        option.addEventListener('click', function() {
            const input = this.querySelector('input[type="radio"]');
            const name = input.name;
            
            // Remove selected class from all options with same name
            document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                radio.closest('.radio-option').classList.remove('selected');
                radio.closest('.radio-option').classList.remove('border-purple-600', 'bg-purple-50');
                radio.closest('.radio-option').classList.add('border-gray-200');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected', 'border-purple-600', 'bg-purple-50');
            this.classList.remove('border-gray-200');
            input.checked = true;
        });
    });
});
</script>
</x-layout>
