<x-layout title="DelWell - Complete Your Profile">
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
                <span class="font-semibold text-purple-600">Step 1 of 4</span>
                <span>Basic Profile</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Let's Get to Know You</h2>
                <p class="text-gray-600">Tell us about yourself to help us find your perfect matches</p>
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

            <form action="{{ route('user.onboarding.save-step1') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                        Age <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="age" id="age" value="{{ old('age', $user->age) }}" 
                           min="18" max="100" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Gender Identity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Gender Identity <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['women' => 'Women', 'men' => 'Men', 'nonbinary' => 'Nonbinary', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('gender_identity', $user->gender_identity) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="gender_identity" value="{{ $value }}" {{ old('gender_identity', $user->gender_identity) == $value ? 'checked' : '' }} required class="hidden">
                            <span class="flex-1 text-center font-medium {{ $value == 'prefer_not_to_say' ? 'text-sm' : '' }}">{{ $label }}</span>
                            <span class="checkmark absolute top-2 right-2 hidden">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Sexual Orientation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sexual Orientation <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach(['heterosexual' => 'Heterosexual', 'lgbtq+' => 'LGBTQ+', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('sexual_orientation', $user->sexual_orientation) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="sexual_orientation" value="{{ $value }}" {{ old('sexual_orientation', $user->sexual_orientation) == $value ? 'checked' : '' }} required class="hidden">
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

                <!-- Interested In -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Interested In <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['men' => 'Men', 'women' => 'Women', 'both' => 'Both'] as $value => $label)
                        <label class="radio-option relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors {{ old('interested_in', $user->interested_in) == $value ? 'border-purple-600 bg-purple-50 selected' : 'border-gray-200' }}">
                            <input type="radio" name="interested_in" value="{{ $value }}" {{ old('interested_in', $user->interested_in) == $value ? 'checked' : '' }} required class="hidden">
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
                <!-- Location -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                            State <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="state" id="state" value="{{ old('state', $user->state) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Bio (Optional) -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                        Bio <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <textarea name="bio" id="bio" rows="4" maxlength="1000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Tell us a bit about yourself (max 1000 characters)</p>
                </div>

                <!-- Navigation Button -->
                <div class="pt-6 mt-6 border-t-2 border-gray-200">
                    <button type="submit" 
                            class="w-full text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center"
                            style="background: linear-gradient(to right, #9333ea, #ec4899);">
                        <span>Continue to Step 2</span>
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
