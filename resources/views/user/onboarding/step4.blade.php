<x-layout title="DelWell - Deeper Profile Quiz">
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Important Notice Banner -->
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-blue-800 mb-1">Final Step - Complete Your Profile</h3>
                    <p class="text-sm text-blue-700">Answer these deeper questions to unlock your full compatibility profile and access your dashboard. All questions are required.</p>
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
                <span class="font-semibold text-purple-600">Step 4 of 4</span>
                <span>Deeper Profile Quiz</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Deeper Profile Questions</h2>
                <p class="text-gray-600">These questions help us understand your compatibility on a deeper level</p>
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

            <form action="{{ route('user.onboarding.save-step4') }}" method="POST" id="quizForm" class="space-y-8">
                @csrf

                @php
                $currentSection = '';
                @endphp

                @foreach($questions as $index => $question)
                    @if($currentSection !== $question['section'])
                        @php $currentSection = $question['section']; @endphp
                        <div class="border-t-2 border-gray-200 pt-6 mt-6">
                            <h3 class="text-xl font-bold text-purple-600 mb-4">{{ $currentSection }}</h3>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <label class="block text-base font-medium text-gray-800 mb-4">
                            {{ $index + 1 }}. {{ $question['question'] }} <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            @if(is_array($question['options']) && !empty($question['options']))
                                @foreach($question['options'] as $optionKey => $optionValue)
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:border-purple-300 transition-all">
                                        <input type="radio" name="answers[{{ $question['id'] }}]" value="{{ $optionKey }}" required class="mr-3 text-purple-600 focus:ring-purple-500">
                                        <span class="text-gray-700">{{ is_string($optionValue) ? $optionValue : json_encode($optionValue) }}</span>
                                    </label>
                                @endforeach
                            @else
                                <p class="text-red-500">No options available for this question.</p>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Navigation Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-6 border-t-2 border-gray-200">
                    <a href="{{ route('user.onboarding.step3') }}" 
                       class="w-full sm:flex-1 bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg hover:bg-gray-300 transition-all duration-200 text-center inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Back</span>
                    </a>
                    <button type="submit" 
                            class="w-full sm:flex-1 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl inline-flex items-center justify-center"
                            style="background: linear-gradient(to right, #9333ea, #ec4899);">
                        <span>Complete Profile</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const requiredQuestions = {{ count($questions) }};
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    
    if (answeredQuestions < requiredQuestions) {
        e.preventDefault();
        alert('Please answer all questions before submitting.');
        return false;
    }
});
</script>
</x-layout>
