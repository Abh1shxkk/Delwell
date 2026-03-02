<x-layout title="User Dashboard - DelWell">
    <div class="container mx-auto px-4 py-12" id="user-dashboard-container">
        
        <!-- Profile Completion Banner (if not 100%) -->
        @if($user->profile_completion < 100)
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-md">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Complete Your Profile
                    </h3>
                    <p class="text-gray-700 mb-4">Your profile is {{ $user->profile_completion }}% complete. Finish your profile to unlock all features and get better matches!</p>
                    
                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Profile Completion</span>
                            <span class="text-sm font-bold text-blue-600">{{ $user->profile_completion }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $user->profile_completion }}%; background: linear-gradient(to right, #3b82f6, #8b5cf6);"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.onboarding.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg"
                       style="background: linear-gradient(to right, #3b82f6, #8b5cf6);">
                        <span>Continue Profile Setup</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Profile Complete Badge -->
        <div class="mb-6 bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Profile Complete! 🎉</h3>
                    <p class="text-gray-700">Your profile is 100% complete. You're all set to discover amazing matches!</p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Your Del Match Code Card -->
                <div class="content-card p-6">

                    <!-- Title -->
                    <h2 class="text-4xl font-bold text-center text-[#3A3A3A] mb-2">Your Del Match Code™</h2>

                    <!-- Del Match Code Display -->
                    <div class="text-center text-5xl font-['Cormorant_Garamond'] font-bold text-[#A3B18A] tracking-widest mb-4">
                        {{ Auth::guard('user')->user()->del_match_code ?? 'FEPLA' }}
                    </div>

                    <!-- Subtitle -->
                    <p class="text-center text-2xl text-gray-700 mb-6 font-['Cormorant_Garamond']">"Your Unique Profile"</p>

                    <!-- Code Breakdown -->
                    <div class="text-left space-y-3 mb-8">
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

                        $delMatchCode = Auth::guard('user')->user()->del_match_code ?? 'FEPLA';
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
                    $userQuizResults = Auth::guard('user')->user()->quiz_results ?? null;
                    if ($userQuizResults) {
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
                    $delMatchCode = Auth::guard('user')->user()->del_match_code ?? 'FEPLA';
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

                {{-- Your Voice, Your Vibe Card - Commented out for now
                <div class="content-card p-6">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-2">Your Voice, Your Vibe</h3>
                    <p class="text-gray-600 mb-4">Bring your profile to life. Record short audio answers to thoughtful prompts to share your authentic self.</p>
                    <a href="#" class="w-full sm:w-auto inline-block text-center bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity">
                        Record Your Prompts
                    </a>
                </div>
                --}}
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <div class="flex items-center space-x-4 mb-4">
                        @php
                        $user = Auth::guard('user')->user();
                        $profileImage = null;
                        if ($user && $user->ai_avatar_path) {
                        $profileImage = url('storage/' . $user->ai_avatar_path);
                        } elseif ($user && $user->profile_image) {
                        $profileImage = url('storage/' . $user->profile_image);
                        }
                        @endphp

                        @if($profileImage)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ $profileImage }}" alt="Your profile" />
                        @else
                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                            <x-profile-placeholder class="w-12 h-12 text-gray-400" />
                        </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold font-['Cormorant_Garamond']">{{ Auth::guard('user')->user()->name ?? 'Jessica L.' }}</h3>
                            <p class="text-sm font-medium text-[#A3B18A]">{{ Auth::guard('user')->user()->del_match_code ?? 'FHMSC' }}</p>
                        </div>
                    </div>
                    <h4 class="font-semibold text-gray-700 mb-2">My Intro Video</h4>
                    @if(Auth::guard('user')->user()->intro_video_path)
                    <div class="relative cursor-pointer group video-thumbnail" 
                         data-video-url="{{ url('storage/' . Auth::guard('user')->user()->intro_video_path) }}" 
                         data-video-title="{{ Auth::guard('user')->user()->name ?? 'Your' }} Intro Video">
                        <video class="rounded-lg w-full object-cover h-32" muted>
                            <source src="{{ url('storage/' . Auth::guard('user')->user()->intro_video_path) }}" type="video/mp4">
                        </video>
                        <div class="absolute inset-0 bg-black/30 rounded-lg group-hover:bg-black/50 transition-colors"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 rounded-full p-3 backdrop-blur-sm group-hover:bg-white/30 transition-colors">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="relative cursor-pointer group border-2 border-dashed border-gray-300 rounded-lg h-32 flex items-center justify-center hover:border-[#A3B18A] transition-colors upload-video-prompt">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Upload your intro video</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Top Matches Card - Commented out, will re-enable later
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-4">Top Matches</h3>
                    <div class="space-y-6">
                        @if(count($topMatches) > 0)
                        @foreach($topMatches as $match)
                        <div class="hover:bg-gray-50 p-2 rounded-lg transition-colors cursor-pointer" data-action="view-discovery">
                            <div class="flex items-center space-x-4">
                                @if($match['has_profile_image'])
                                <img class="h-12 w-12 rounded-full object-cover" src="{{ $match['profile_image'] }}" alt="{{ $match['name'] }}" />
                                @else
                                <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                                    <x-profile-placeholder class="w-8 h-8 text-gray-400" />
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $match['name'] }}</p>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm text-gray-500">Score: {{ $match['match_percentage'] }}%</p>
                                        <span class="text-gray-300">|</span>
                                        <p class="text-sm font-medium text-[#A3B18A]">{{ $match['del_match_code'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($match['has_intro_video'])
                            <div class="relative mt-3 cursor-pointer group video-thumbnail" 
                                 data-video-url="{{ url('storage/' . $match['intro_video_path']) }}" 
                                 data-video-title="{{ $match['name'] }}'s Intro Video">
                                <video class="rounded-lg w-full object-cover h-24" muted>
                                    <source src="{{ url('storage/' . $match['intro_video_path']) }}" type="video/mp4">
                                </video>
                                <div class="absolute inset-0 bg-black/30 rounded-lg group-hover:bg-black/50 transition-colors"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-white/20 rounded-full p-2 backdrop-blur-sm group-hover:bg-white/30 transition-colors">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="relative mt-3 cursor-pointer group">
                                <div class="rounded-lg w-full h-24 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-6 h-6 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-500 text-xs">No video yet</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <x-profile-placeholder class="w-16 h-16 text-gray-300 mx-auto" />
                            </div>
                            <h4 class="font-semibold text-gray-700 mb-2">No Matches Yet</h4>
                            <p class="text-sm text-gray-500 mb-4">Complete your profile to discover your top matches</p>
                            <a href="{{ route('user.discovery') }}" class="inline-block bg-[#A3B18A] text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity text-sm">
                                Discover Matches
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                --}}

                <!-- Deepen Your Profile Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-4">Deepen Your Profile</h3>
                    <p class="text-gray-600 mb-4">Answer more in-depth questions about your attachment style, values, and lifestyle to unlock higher quality matches.</p>
                    <a href="{{ route('user.continue-quiz') }}"
                        class="text-white font-semibold text-center py-3 px-8 rounded-full text-lg shadow-lg inline-block transition-opacity duration-300 hover:opacity-90 w-full"
                        style="background-color: #A3B18A;">
                        @if($hasCompletedFullQuiz)
                            Retake Deeper Quiz
                        @else
                            Start Deeper Quiz
                        @endif
                    </a>
                </div>

                <!-- Circle Perspective Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-4">Your Circle's Perspective</h3>
                    <p class="text-gray-600 mb-4">Trusted people from all your relationships convey the pros and cons through insights.</p>
                    
                    @php
                    // Check if user has any circle members or insights
                    $hasCircleInsights = isset($circleCount) && $circleCount > 0;
                    @endphp
                    
                    @if($hasCircleInsights)
                        <div style="background-color: rgba(163, 177, 138, 0.1); padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #A3B18A; margin-bottom: 1rem;">
                            <p style="color: #374151; font-style: italic; line-height: 1.625;">You have {{ $circleCount }} {{ $circleCount == 1 ? 'person' : 'people' }} in your circle! Their insights and recommendations will appear here as they provide feedback about your dating journey.</p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('user.circle') }}" class="inline-block bg-[#A3B18A] text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity text-sm">
                                View Your Circle
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-700 mb-2">No Circle Insights Yet</h4>
                            <p class="text-sm text-gray-500 mb-4">Invite trusted friends and family to get personalized insights about your relationships.</p>
                            <a href="{{ route('user.circle') }}" class="inline-block bg-[#FFC09F] text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity text-sm">
                                Build Your Circle
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Profile Settings Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-4">Profile Settings</h3>
                    <p class="text-gray-600 mb-4">Manage your profile, upload media, change password, and account preferences.</p>
                    <a href="{{ route('user.profile-settings') }}"
                        class="w-full block text-center bg-[#A3B18A] text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity"
                        style="background-color: #FFC09F;">
                        Manage Settings
                    </a>
                </div>

                <!-- Your DelWell Circle Card -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200/80">
                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond'] mb-4">Your DelWell Circle</h3>
                    <p class="text-gray-600 mb-4">Invite trusted friends and family to help you on your journey.</p>
                    <a href="{{ route('user.circle') }}"
                        class="w-full block text-center text-white font-bold py-2 px-4 rounded-full transition duration-300 hover:opacity-90"
                        style="background-color: #FFC09F;">
                        Invite Your Circle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Customization Modal -->
    <div id="avatar-modal" class="fixed inset-0 bg-black/50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 sm:p-12 text-center max-w-md mx-4 shadow-xl">
            <h3 class="text-2xl font-bold mb-2">Customize Your Avatar</h3>
            <p class="text-gray-600 mb-6">Update your physical characteristics to regenerate your AI avatar.</p>

            <form id="avatar-form" class="text-left space-y-4">
                @csrf
                <div>
                    <label class="font-medium text-sm text-gray-700">Build</label>
                    <input type="text" id="avatar-build" class="w-full p-3 border-2 border-gray-200 rounded-lg mt-1 focus:ring-2 focus:ring-peach focus:border-peach" placeholder="e.g., slender, athletic, average" required>
                </div>
                <div>
                    <label class="font-medium text-sm text-gray-700">Skin Tone</label>
                    <input type="text" id="avatar-skin" class="w-full p-3 border-2 border-gray-200 rounded-lg mt-1 focus:ring-2 focus:ring-peach focus:border-peach" placeholder="e.g., fair, olive, dark brown" required>
                </div>
                <div>
                    <label class="font-medium text-sm text-gray-700">Hair Color & Style</label>
                    <input type="text" id="avatar-hair" class="w-full p-3 border-2 border-gray-200 rounded-lg mt-1 focus:ring-2 focus:ring-peach focus:border-peach" placeholder="e.g., long curly brown, short blond" required>
                </div>
                <div>
                    <label class="font-medium text-sm text-gray-700">Eye Color</label>
                    <input type="text" id="avatar-eyes" class="w-full p-3 border-2 border-gray-200 rounded-lg mt-1 focus:ring-2 focus:ring-peach focus:border-peach" placeholder="e.g., blue, green, dark brown" required>
                </div>

                <div class="flex space-x-3 !mt-6">
                    <button type="button" onclick="closeAvatarModal()" class="flex-1 btn-secondary">Cancel</button>
                    <button type="submit" class="flex-1 btn-primary" id="regenerate-btn">
                        <span class="btn-text">Regenerate Avatar</span>
                        <span class="btn-loading hidden">
                            <svg class="animate-spin h-4 w-4 text-white inline mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAvatarModal() {
            // Load existing avatar data
            fetch('/api/avatar/info')
                .then(response => response.json())
                .then(data => {
                    if (data.generation_data && data.generation_data.avatar_data) {
                        const avatarData = data.generation_data.avatar_data;
                        document.getElementById('avatar-build').value = avatarData.build || '';
                        document.getElementById('avatar-skin').value = avatarData.skin || '';
                        document.getElementById('avatar-hair').value = avatarData.hair || '';
                        document.getElementById('avatar-eyes').value = avatarData.eyes || '';
                    }
                })
                .catch(error => console.log('Could not load existing avatar data'));

            const modal = document.getElementById('avatar-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAvatarModal() {
            const modal = document.getElementById('avatar-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Avatar form submission
        document.getElementById('avatar-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('regenerate-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');

            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            const formData = {
                avatar_data: {
                    build: document.getElementById('avatar-build').value,
                    skin: document.getElementById('avatar-skin').value,
                    hair: document.getElementById('avatar-hair').value,
                    eyes: document.getElementById('avatar-eyes').value
                },
                _token: document.querySelector('input[name="_token"]').value
            };

            fetch('/api/avatar/regenerate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update avatar image
                        const avatarImg = document.querySelector('img[alt="AI Generated Avatar"]');
                        if (avatarImg) {
                            avatarImg.src = data.avatar_url + '?t=' + Date.now(); // Add timestamp to force reload
                        }

                        closeAvatarModal();

                        // Show success message
                        alert('Avatar regenerated successfully!');
                    } else {
                        alert('Error: ' + (data.error || 'Failed to regenerate avatar'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to regenerate avatar. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    btn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                });
        });

        // Close modal when clicking outside
        document.getElementById('avatar-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAvatarModal();
            }
        });

        // Handle top matches click to go to discovery page
        document.addEventListener('click', function(e) {
            // Check if the click is on a video thumbnail - if so, don't redirect
            const videoThumbnail = e.target.closest('.video-thumbnail');
            if (videoThumbnail) {
                e.preventDefault();
                e.stopPropagation();
                const videoUrl = videoThumbnail.getAttribute('data-video-url');
                const videoTitle = videoThumbnail.getAttribute('data-video-title');
                openVideoModal(videoUrl, videoTitle);
                return;
            }
            
            const element = e.target.closest('[data-action="view-discovery"]');
            if (element) {
                window.location.href = '{{ route("user.discovery") }}';
            }
        });

        // Handle upload video prompt click
        document.addEventListener('click', function(e) {
            const element = e.target.closest('.upload-video-prompt');
            if (element) {
                window.location.href = '{{ route("user.profile-settings") }}';
            }
        });

        // Video Modal Functions
        function openVideoModal(videoUrl, title) {
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('modal-video');
            const titleEl = document.getElementById('modal-video-title');
            
            video.src = videoUrl;
            titleEl.textContent = title;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeVideoModal() {
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('modal-video');
            
            modal.classList.add('hidden');
            video.pause();
            video.src = '';
            document.body.classList.remove('overflow-hidden');
        }

        // Handle video thumbnail clicks
        document.addEventListener('click', function(e) {
            const thumbnail = e.target.closest('.video-thumbnail');
            if (thumbnail) {
                e.preventDefault();
                const videoUrl = thumbnail.getAttribute('data-video-url');
                const title = thumbnail.getAttribute('data-video-title');
                openVideoModal(videoUrl, title);
            }
        });

        // Close modal when clicking outside or on close button
        document.addEventListener('click', function(e) {
            if (e.target.id === 'video-modal' || e.target.closest('.video-modal-close')) {
                closeVideoModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>

    <!-- Video Lightbox Modal -->
    <div id="video-modal" class="fixed inset-0 bg-black/80 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6" style="background-color: #fff2ec;">
                    <h3 id="modal-video-title" class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] drop-shadow-sm">Video Title</h3>
                    <button class="video-modal-close text-white hover:text-gray-300 transition-colors p-2 rounded-full hover:bg-white/10">
                        <svg class="w-6 h-6 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Video Container -->
                <div class="relative bg-black">
                    <video 
                        id="modal-video" 
                        class="w-full h-auto max-h-[70vh] object-contain" 
                        controls 
                        autoplay
                        preload="metadata"
                    >
                        Your browser does not support the video tag.
                    </video>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-4 bg-gray-50 flex justify-end space-x-3" style="background-color: #fff2ec;">
                    <button class="video-modal-close bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layout>