<x-layout title="DelWell - Profile Complete!">
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Success Animation -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4 animate-bounce">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">🎉 Profile Complete!</h1>
            <p class="text-xl text-gray-600 mb-8">
                Congratulations! Your profile is now 100% complete and you're ready to discover amazing matches.
            </p>

            <!-- Profile Summary -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">What's Next?</h3>
                <div class="space-y-3 text-left">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Discover Your Matches</p>
                            <p class="text-sm text-gray-600">Browse through curated matches based on your compatibility profile</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Build Your Circle</p>
                            <p class="text-sm text-gray-600">Invite trusted friends and family to support your dating journey</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800">Enhance Your Profile</p>
                            <p class="text-sm text-gray-600">Add photos, videos, and audio prompts to showcase your personality</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Completion Badge -->
            <div class="mb-8">
                <div class="inline-block text-white px-6 py-3 rounded-full font-semibold shadow-lg" style="background: linear-gradient(to right, #3b82f6, #2563eb);">
                    ✨ Profile Completion: 100%
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('user.dashboard') }}" 
                   class="block w-full text-white font-bold py-4 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-center"
                   style="background: linear-gradient(to right, #9333ea, #ec4899);">
                    <span>Go to Dashboard</span>
                    <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                
                <a href="{{ route('user.discovery') }}" 
                   class="block w-full bg-white border-2 border-purple-600 text-purple-600 font-bold py-4 px-6 rounded-lg hover:bg-purple-50 transition-all duration-200 text-center">
                    <span>Discover Matches</span>
                    <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </a>
            </div>

            <!-- Welcome Message -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-gray-600">
                    Welcome to DelWell, {{ $user->name }}! We're excited to help you find meaningful connections. 💜
                </p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}
</style>
</x-layout>
