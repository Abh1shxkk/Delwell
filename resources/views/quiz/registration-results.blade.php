<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Del Match Code™ - DelWell</title>
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #3a3a3a;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">

    <!-- Header -->
    <header class="w-full px-6 py-4 bg-white border-b border-gray-200">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">DelWell™</h1>
            <nav class="flex space-x-6 text-sm text-gray-600">
                <a href="{{ route('user.dashboard') }}" class="hover:text-gray-800">Dashboard</a>
                <a href="{{ route('user.discovery') }}" class="hover:text-gray-800">Discover</a>
                <a href="{{ route('user.self-discovery') }}" class="hover:text-gray-800">Self Discovery</a>
                <a href="#" class="hover:text-gray-800">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            
            <!-- Code Result Card -->
            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 max-w-2xl mx-auto my-8">
                
                <!-- Title -->
                <h2 class="text-4xl font-bold text-center text-[#3A3A3A] mb-2">Your Del Match Code™</h2>
                
                <!-- Del Match Code Display -->
                <div class="text-center text-5xl font-['Cormorant_Garamond'] font-bold text-[#A3B18A] tracking-widest mb-4">
                    {{ $delMatchCode }}
                </div>
                
                <!-- Subtitle -->
                <p class="text-center text-2xl text-gray-700 mb-6 font-['Cormorant_Garamond']">"Your Unique Profile"</p>
                
                <!-- Code Breakdown -->
                <div class="text-left space-y-3 mb-8 max-w-2xl mx-auto">
                    @php
                        // Get Del Match Code questions from database
                        $delMatchQuestions = \App\Models\QuizQuestion::where('section', 'Del Match Code™')
                            ->where('is_active', true)
                            ->orderBy('order')
                            ->get();
                        
                        // Build letter meanings from database
                        $letterMeanings = [];
                        $colors = ['orange', 'yellow', 'blue', 'purple', 'green'];
                        
                        foreach($delMatchQuestions as $index => $question) {
                            $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                            
                            if (is_array($options)) {
                                foreach($options as $option) {
                                    $value = is_array($option) ? ($option['value'] ?? '') : $option;
                                    $text = is_array($option) ? ($option['text'] ?? $option['label'] ?? '') : $option;
                                    
                                    if ($value && $text) {
                                        $letterMeanings[$value] = [
                                            'title' => $text,
                                            'desc' => $text,
                                            'color' => $colors[$index % count($colors)]
                                        ];
                                    }
                                }
                            }
                        }
                        
                        $codeLetters = str_split($delMatchCode);
                    @endphp
                    
                    @foreach($codeLetters as $index => $letter)
                        @if(isset($letterMeanings[$letter]))
                            @php $meaning = $letterMeanings[$letter]; @endphp
                            <div class="flex items-start">
                                <span class="text-2xl font-bold" style="color: #FFC09F; margin-right: 1rem;">{{ $letter }}</span>
                                <div>
                                    <span class="text-gray-600">{{ $meaning['title'] }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                @php
                    // Generate dynamic content based on actual user's quiz answers and Del Match Code
                    $codeAnalysis = [
                        'summary' => '',
                        'growth_tip' => '',
                        'compatibility' => ''
                    ];
                    
                    // Get user's actual quiz answers if available
                    $userAnswers = [];
                    if (isset($userQuizResults) && $userQuizResults) {
                        $quizData = is_string($userQuizResults) ? json_decode($userQuizResults, true) : $userQuizResults;
                        $userAnswers = $quizData['answers'] ?? [];
                    }
                    
                    // Build traits from user's actual answers
                    $traits = [];
                    $selectedOptions = [];
                    
                    foreach($delMatchQuestions as $question) {
                        $questionId = $question->question_id;
                        $userAnswer = $userAnswers["q{$questionId}"] ?? null;
                        
                        if ($userAnswer && isset($letterMeanings[$userAnswer])) {
                            $traits[] = strtolower($letterMeanings[$userAnswer]['title']);
                            $selectedOptions[] = $letterMeanings[$userAnswer]['title'];
                        }
                    }
                    
                    // Build summary based on user's actual selected options
                    if (count($selectedOptions) > 0) {
                        $codeAnalysis['summary'] = "You bring " . strtolower(implode(', ', array_slice($selectedOptions, 0, -1)));
                        if (count($selectedOptions) > 1) {
                            $codeAnalysis['summary'] .= " and " . strtolower(end($selectedOptions));
                        }
                        $codeAnalysis['summary'] .= " qualities to relationships. Your combination of traits creates a unique relationship style that attracts partners who value your authentic approach to connection.";
                    } else {
                        $codeAnalysis['summary'] = "Your Del Match Code™ represents a unique combination of relationship preferences and values that will help you find meaningful connections.";
                    }
                    
                    // Generate growth tip based on code pattern
                    $codePattern = $delMatchCode;
                    $growthTips = [
                        'Focus on maintaining balance between your relationship goals and personal growth.',
                        'Consider how your communication style impacts your connections with others.',
                        'Embrace both your strengths and areas for growth in relationships.',
                        'Stay open to different perspectives while honoring your core values.',
                        'Practice vulnerability while maintaining healthy boundaries.'
                    ];
                    $codeAnalysis['growth_tip'] = $growthTips[array_rand($growthTips)];
                    
                    // Generate compatibility based on code
                    $codeAnalysis['compatibility'] = "Your code " . $delMatchCode . " indicates compatibility with partners who share similar values around commitment, communication, and life goals. Look for connections that complement your relationship style and support your growth.";
                @endphp
                
                <!-- Summary Section -->
                <div class="bg-[#F9F7F3] p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-lg mb-2">Summary</h3>
                    <p class="text-gray-600">
                        {{ $codeAnalysis['summary'] ?: 'You bring a unique combination of traits to relationships that creates meaningful connections.' }}
                    </p>
                </div>
                
                <!-- Growth Tip -->
                <div class="bg-[#A3B18A]/20 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-lg mb-2">Growth Tip</h3>
                    <p class="text-gray-600">
                        {{ $codeAnalysis['growth_tip'] ?: 'Continue developing self-awareness and communication skills to deepen your relationships.' }}
                    </p>
                </div>
                
                <!-- Compatibility Drops -->
                <div class="bg-[#FFC09F]/20 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Compatibility Drops</h3>
                    <p class="text-gray-600">
                        {{ $codeAnalysis['compatibility'] }}
                    </p>
                </div>
                
            </div>
            
            <!-- Action Section -->
            <div class="text-center">
                <p class="mb-6 text-gray-600">You've discovered your core code! Now, let's deepen your profile for even better matches.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a 
                        href="{{ route('user.continue-quiz') }}" 
                        class="text-white font-bold py-3 px-8 rounded-full text-lg shadow-lg inline-block transition-opacity duration-300 hover:opacity-90"
                        style="background-color: #A3B18A;"
                    >
                        Deepen Profile Now
                    </a>
                    <button 
                        onclick="handleContinueToDashboard()"
                        class="bg-transparent font-bold py-3 px-8 rounded-full text-lg transition-colors"
                        style="border: 2px solid #A3B18A; color: #A3B18A;"
                        onmouseover="this.style.backgroundColor='rgba(163, 177, 138, 0.1)'"
                        onmouseout="this.style.backgroundColor='transparent'"
                    >
                        I'll Do It Later
                    </button>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full px-6 py-8 bg-white border-t border-gray-200 mt-16">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold text-gray-800">DelWell™</h3>
                </div>
                <div class="flex space-x-6 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-800">About</a>
                    <a href="#" class="hover:text-gray-800">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-800">Licensing</a>
                    <a href="#" class="hover:text-gray-800">Contact</a>
                </div>
            </div>
            <div class="text-center mt-6 text-sm text-gray-500">
                © 2025 DelWell™ All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        function handleContinueToDashboard() {
            // Add a small delay to show the button click, then redirect
            setTimeout(function() {
                window.location.href = "{{ route('user.dashboard') }}";
            }, 500);
        }

        // Auto-redirect after 30 seconds if user doesn't interact
        setTimeout(function() {
            handleContinueToDashboard();
        }, 30000);
    </script>

</body>
</html>
