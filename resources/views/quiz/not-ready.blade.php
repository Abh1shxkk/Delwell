<x-layout title="Focus on Growth - DelWell">
    <x-slot name="styles">
        <style>
            body {
                background: linear-gradient(135deg, #fed7aa 0%, #fef3c7 100%);
                min-height: 100vh;
            }
        </style>
    </x-slot>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-3xl w-full">
            <div class="content-card p-8 md:p-12 text-center">
                
                <!-- Icon -->
                <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>

                <h2 class="text-4xl font-bold mb-6 text-orange-800">Beautiful Self-Awareness </h2>
                
                <p class="text-lg text-orange-700 mb-8 leading-relaxed">
                    Thank you for being honest with yourself. Recognizing that you're in a growth phase shows incredible self-awareness. 
                    The best relationships often come when we're grounded in who we are.
                </p>

                <!-- Growth Resources -->
                <div class="bg-white p-8 rounded-lg mb-8 text-left">
                    <h3 class="text-xl font-semibold mb-6 text-center text-gray-800">Resources for Your Journey</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-700">Self-Discovery</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>• <strong>Therapy & Counseling:</strong> Professional guidance for deeper understanding</li>
                                <li>• <strong>Mindfulness Practices:</strong> Meditation, yoga, or breathwork</li>
                                <li>• <strong>Journaling:</strong> Daily reflection and emotional processing</li>
                            </ul>
                        </div>
                        
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-700">Personal Growth</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>• <strong>Read & Learn:</strong> Books on emotional intelligence and relationships</li>
                                <li>• <strong>Connect with Community:</strong> Support groups or workshops</li>
                                <li>• <strong>Creative Expression:</strong> Art, music, writing, or other outlets</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Book Recommendations -->
                <div class="bg-orange-50 p-6 rounded-lg mb-8">
                    <h4 class="font-semibold mb-4">Recommended Reading</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <div class="font-medium">"Attached"</div>
                            <div class="text-gray-600">by Amir Levine</div>
                        </div>
                        <div class="text-center">
                            <div class="font-medium">"The Gifts of Imperfection"</div>
                            <div class="text-gray-600">by Brené Brown</div>
                        </div>
                        <div class="text-center">
                            <div class="font-medium">"Self-Compassion"</div>
                            <div class="text-gray-600">by Kristin Neff</div>
                        </div>
                    </div>
                </div>

                <!-- Future Promise -->
                <div class="border-t border-gray-200 pt-8">
                    <p class="text-gray-600 mb-6">
                        When you feel ready to explore conscious connections, DelWell will be here. 
                        Your growth journey is valuable, and future you (and your future partner) will thank you for this investment.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('home') }}" class="btn-primary">
                            Return to DelWell
                        </a>
                        <a href="{{ route('quiz.start') }}" class="btn-secondary">
                            Retake Quiz Later
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
                    <p>Questions about your results? <a href="#" class="text-orange-600 hover:underline">Contact our support team</a></p>
                </div>
            </div>
        </div>
    </div>
</x-layout>