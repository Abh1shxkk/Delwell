<x-layout title="DelWell - Conscious Connections">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">
            <!-- Main Card -->
            <div class="content-card p-8 md:p-12 text-center">
                <div class="mb-8">
                    <div class="w-20 h-20 bg-matcha rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-4">Welcome to Your Compatibility Journey</h2>
                    <p class="text-lg text-light max-w-2xl mx-auto leading-relaxed">
                        Before we help you find meaningful connections, let's understand what makes you unique. 
                        Our scientifically-designed quiz will create your personalized Del Match Code™ and compatibility profile.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-matcha rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white font-bold">1</span>
                        </div>
                        <h3 class="font-semibold mb-2">Del Match Code™</h3>
                        <p class="text-sm text-light">Your unique 5-letter compatibility code</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-matcha rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white font-bold">2</span>
                        </div>
                        <h3 class="font-semibold mb-2">Attachment Style</h3>
                        <p class="text-sm text-light">Understand how you connect in relationships</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-matcha rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white font-bold">3</span>
                        </div>
                        <h3 class="font-semibold mb-2">Values & Energy</h3>
                        <p class="text-sm text-light">Discover your core values and energy style</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-12 h-12 bg-matcha rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white font-bold">4</span>
                        </div>
                        <h3 class="font-semibold mb-2">Lifestyle Match</h3>
                        <p class="text-sm text-light">Find compatible lifestyle preferences</p>
                    </div>
                </div>

                <!-- Quiz Info -->
                <div class="bg-gray-50 p-6 rounded-lg mb-8">
                    <h3 class="font-semibold mb-4">What to Expect</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-matcha mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>39 thoughtful questions</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-matcha mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>10-15 minutes to complete</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-matcha mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Personalized results</span>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="space-y-4">
                    <a href="{{ route('quiz.start') }}" class="btn-primary text-lg inline-block px-8 py-4">
                        Start My Compatibility Quiz
                    </a>
                    <p class="text-sm text-light">
                        Your answers are private and secure. We use this information only to create better matches for you.
                    </p>
                </div>

                <!-- Bottom note -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-xs text-light">
                        Already have an account? <a href="{{ route('login') }}" class="text-peach hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-layout>