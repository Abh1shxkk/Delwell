<x-layout title="DelWell - Relationship Preferences">
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
                <span class="font-semibold text-purple-600">Step 2 of 4</span>
                <span>Relationship Preferences</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">What Are You Looking For?</h2>
                <p class="text-gray-600">Help us understand your relationship goals and preferences</p>
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

            <form action="{{ route('user.onboarding.save-step2') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Relationship Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Looking For <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['serious' => 'Serious Relationship', 'casual' => 'Casual Dating', 'friendship' => 'Friendship', 'open' => 'Open to Anything'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('relationship_type', $user->relationship_type) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="relationship_type" value="{{ $value }}" {{ old('relationship_type', $user->relationship_type) == $value ? 'checked' : '' }} required class="hidden">
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

                <!-- Relationship Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Current Relationship Status <span class="text-red-500">*</span>
                    </label>
                    <select name="relationship_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select status...</option>
                        @foreach(['single' => 'Single', 'divorced' => 'Divorced', 'separated' => 'Separated', 'widowed' => 'Widowed', 'in_a_relationship' => 'In a Relationship', 'it_is_complicated' => "It's Complicated"] as $value => $label)
                        <option value="{{ $value }}" {{ old('relationship_status', $user->relationship_status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Has Children -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Do you have children? <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['yes' => 'Yes', 'no' => 'No'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('has_children', $user->has_children) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="has_children" value="{{ $value }}" {{ old('has_children', $user->has_children) == $value ? 'checked' : '' }} required class="hidden">
                            <span class="flex-1 text-center font-medium">{{ $label }}</span>
                            <span class="checkmark absolute top-2 right-2 hidden">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Age Preferences -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Age Preference <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="age_min" class="block text-xs text-gray-600 mb-1">Minimum Age</label>
                            <input type="number" name="age_min" id="age_min" value="{{ old('age_min', $user->age_min) }}" 
                                   min="18" max="100" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="age_max" class="block text-xs text-gray-600 mb-1">Maximum Age</label>
                            <input type="number" name="age_max" id="age_max" value="{{ old('age_max', $user->age_max) }}" 
                                   min="18" max="100" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Distance Preference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Distance Willing to Date <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['10' => '10 miles', '25' => '25 miles', '50' => '50 miles', 'long' => 'Long Distance OK'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('distance_preference', $user->distance_preference) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="distance_preference" value="{{ $value }}" {{ old('distance_preference', $user->distance_preference) == $value ? 'checked' : '' }} required class="hidden">
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

                <!-- Navigation Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-6 border-t-2 border-gray-200">
                    <a href="{{ route('user.onboarding.step1') }}" 
                       class="w-full sm:flex-1 bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg hover:bg-gray-300 transition-all duration-200 text-center inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Back</span>
                    </a>
                    <button type="submit" 
                            class="w-full sm:flex-1 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center"
                            style="background: linear-gradient(to right, #9333ea, #ec4899);">
                        <span>Continue to Step 3</span>
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
