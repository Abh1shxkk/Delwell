<x-layout title="DelWell - Create Your Del Match Code™">
    <x-slot name="styles">
        <style>
            body {
                background-color: #f9fafb;
                min-height: 100vh;
            }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <!-- Quiz Header -->
    <div class="w-full p-4 sm:p-6 border-b border-gray-200 bg-white">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-light">Create Your Del Match Code™</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-light">
                    Question <span id="current-question">1</span> of <span id="total-questions">5</span>
                </div>
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-matcha h-2 rounded-full transition-all duration-300" style="width: 20%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/80">
        <div class="max-w-4xl w-full">

                <!-- Quiz Form -->
                <form id="signup-quiz-form" method="POST" action="{{ route('signup.submit') }}">
                    @csrf

                    <!-- Question Container -->
                    <div id="question-container" class="content-card p-8 md:p-12 text-center min-h-[400px] flex flex-col justify-center">

                        <!-- Question -->
                        <div class="mb-8">
                            <h2 id="question-text" class="text-2xl md:text-3xl font-semibold mb-6 leading-relaxed">
                                Loading question...
                            </h2>
                        </div>

                        <!-- Options -->
                        <div id="options-container" class="space-y-4 max-w-2xl mx-auto">
                            <!-- Options will be dynamically inserted here -->
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center mt-12 pt-8 border-t border-gray-200">
                            <button type="button" id="prev-btn" class="btn-secondary" disabled>
                                Previous
                            </button>

                            <div class="flex space-x-2">
                                <button type="button" id="next-btn" class="btn-primary" disabled>
                                    Next Question
                                </button>
                                <button type="submit" id="submit-btn" class="btn-primary hidden">
                                    Create My Code & Continue
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </div>

    <!-- Pause Modal -->
    <div id="pause-modal" class="fixed inset-0 bg-black/50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 sm:p-12 text-center max-w-md mx-4 shadow-xl">
            <div class="mx-auto w-16 h-16 bg-matcha rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">A Moment to Pause</h3>
            <p class="text-light text-lg mb-8">Before you see your code, take a pause. Inhale for 4, hold for 4, exhale for 4, hold for 4. One round is enough.</p>
            <button id="pause-continue-btn" class="btn-primary">Continue</button>
        </div>
    </div>

    <!-- Avatar Generation Modal -->
    <div id="avatar-modal" class="fixed inset-0 bg-black/50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 sm:p-12 text-center max-w-md mx-4 shadow-xl">
            <h3 class="text-2xl font-bold mb-2">Create Your Avatar</h3>
            <p class="text-light mb-6">Describe your physical appearance. Our AI will generate an artistic avatar for you.</p>
            <form id="avatar-form" class="text-left space-y-4">
                <div>
                    <label class="font-medium text-sm">Build</label>
                    <input type="text" id="avatar-build" class="w-full p-2 border-2 rounded-lg mt-1" placeholder="e.g., slender, athletic, average" required>
                </div>
                <div>
                    <label class="font-medium text-sm">Skin Tone</label>
                    <input type="text" id="avatar-skin" class="w-full p-2 border-2 rounded-lg mt-1" placeholder="e.g., fair, olive, dark brown" required>
                </div>
                <div>
                    <label class="font-medium text-sm">Hair Color & Style</label>
                    <input type="text" id="avatar-hair" class="w-full p-2 border-2 rounded-lg mt-1" placeholder="e.g., long curly brown, short blond" required>
                </div>
                <div>
                    <label class="font-medium text-sm">Eye Color</label>
                    <input type="text" id="avatar-eyes" class="w-full p-2 border-2 rounded-lg mt-1" placeholder="e.g., blue, green, dark brown" required>
                </div>
                <button type="submit" class="btn-primary w-full !mt-6">Generate My Avatar & Continue</button>
            </form>
        </div>
    </div>

    <!-- Registration Form Section (Hidden initially) -->
    <div id="registration-section" class="min-h-screen items-center justify-center p-4 hidden">
        <div class="max-w-4xl w-full">
            <div class="content-card p-8 md:p-12">
                <div class="text-center mb-8">
                    <div class="inline-block bg-matcha text-white px-4 py-2 rounded-full text-sm font-medium mb-4">
                        Your Del Match Code™: <span id="generated-code" class="font-bold"></span>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Complete Your Registration</h2>
                    <p class="text-light">Fill in your details to create your DelWell account.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="registration-form" method="POST" action="{{ route('register.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-light mb-2">First Name *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-light mb-2">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-light mb-2">Username *</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-light mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-light mb-2">Password *</label>
                            <input type="password" id="password" name="password" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-light mb-2">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="age" class="block text-sm font-medium text-light mb-2">Age *</label>
                            <input type="number" id="age" name="age" value="{{ old('age') }}" min="18" max="100" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-light mb-2">Gender *</label>
                            <select id="gender" name="gender" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="interested_in" class="block text-sm font-medium text-light mb-2">Interested In *</label>
                            <select id="interested_in" name="interested_in" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                                <option value="">Select Interest</option>
                                <option value="men" {{ old('interested_in') == 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ old('interested_in') == 'women' ? 'selected' : '' }}>Women</option>
                                <option value="both" {{ old('interested_in') == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-light mb-2">City *</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-light mb-2">State</label>
                            <input type="text" id="state" name="state" value="{{ old('state') }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-center">
                        <input type="checkbox" id="accepted_terms" name="accepted_terms" required class="w-4 h-4 text-peach bg-gray-100 border-gray-300 rounded focus:ring-peach focus:ring-2">
                        <label for="accepted_terms" class="ml-2 text-sm font-medium text-gray-900">I accept the Terms of Service and Privacy Policy *</label>
                    </div>

                    <button type="submit" class="btn-primary w-full">Create My Account</button>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <!-- Quiz data -->
        <script type="application/json" id="quiz-data">
            @json($delMatchQuestions)
        </script>

        <script>
            // Quiz data and state
            const questions = JSON.parse(document.getElementById('quiz-data').textContent);
            let currentQuestionIndex = 0;
            let answers = {};

            // DOM elements
            const currentQuestionEl = document.getElementById('current-question');
            const totalQuestionsEl = document.getElementById('total-questions');
            const progressBar = document.getElementById('progress-bar');
            const questionText = document.getElementById('question-text');
            const optionsContainer = document.getElementById('options-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            // Initialize quiz
            function initQuiz() {
                totalQuestionsEl.textContent = questions.length;
                showQuestion(0);
            }

            // Show specific question
            function showQuestion(index) {
                if (index < 0 || index >= questions.length) return;

                const question = questions[index];
                currentQuestionIndex = index;

                // Update UI elements
                currentQuestionEl.textContent = index + 1;
                progressBar.style.width = `${((index + 1) / questions.length) * 100}%`;
                questionText.textContent = question.question;

                // Generate options
                generateOptions(question);

                // Update navigation buttons
                updateNavigationButtons();
            }

            // Generate option buttons
            function generateOptions(question) {
                optionsContainer.innerHTML = '';

                question.options.forEach((option, optionIndex) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'quiz-option w-full text-left p-4 rounded-lg border-2 border-gray-200 hover:border-peach transition-all duration-200';
                    button.textContent = option.text;
                    button.onclick = () => selectOption(question.id, option.value, button);

                    // Check if this option was previously selected
                    if (answers[question.id] === option.value) {
                        button.classList.add('selected');
                        button.classList.remove('border-gray-200');
                        button.classList.add('border-peach', 'bg-peach');
                    }

                    optionsContainer.appendChild(button);
                });
            }

            // Handle option selection
            function selectOption(questionId, value, buttonEl) {
            // Store answer
            answers[questionId] = value;

            // Update button styling
            document.querySelectorAll('.quiz-option').forEach(btn => {
                btn.classList.remove('selected', 'border-peach', 'bg-peach', 'text-white');
                btn.classList.add('border-gray-200');
            });

            buttonEl.classList.add('selected', 'border-peach', 'bg-peach');
            buttonEl.classList.remove('border-gray-200');

            // Enable next button
            updateNavigationButtons();

            // Check if this is the 5th question (last question in Del Match Code)
            if (currentQuestionIndex === questions.length - 1) {
                // Show pause modal after a short delay
                setTimeout(() => {
                    showPauseModal();
                }, 500);
            } else {
                // Auto-advance after selection for other questions
                setTimeout(() => {
                    if (currentQuestionIndex < questions.length - 1) {
                        nextQuestion();
                    }
                }, 500);
            }
        }

        // Update navigation buttons
        function updateNavigationButtons() {
            const currentQuestion = questions[currentQuestionIndex];
            const hasAnswer = answers[currentQuestion.id];

            // Previous button
            prevBtn.disabled = currentQuestionIndex === 0;

            // Next/Submit button
            if (currentQuestionIndex === questions.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                submitBtn.disabled = !hasAnswer;
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                nextBtn.disabled = !hasAnswer;
            }
        }

        // Navigation functions
        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                showQuestion(currentQuestionIndex + 1);
            }
        }

        function previousQuestion() {
            if (currentQuestionIndex > 0) {
                showQuestion(currentQuestionIndex - 1);
            }
        }

        // Event listeners
        nextBtn.addEventListener('click', nextQuestion);
        prevBtn.addEventListener('click', previousQuestion);

        // Modal functions
        function showPauseModal() {
            const modal = document.getElementById('pause-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hidePauseModal() {
            const modal = document.getElementById('pause-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function showAvatarModal() {
            const modal = document.getElementById('avatar-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideAvatarModal() {
            const modal = document.getElementById('avatar-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Modal event listeners
        document.getElementById('pause-continue-btn').addEventListener('click', function() {
            hidePauseModal();
            showAvatarModal();
        });

        // Avatar form submission
        document.getElementById('avatar-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get avatar data
            const avatarData = {
                build: document.getElementById('avatar-build').value,
                skin: document.getElementById('avatar-skin').value,
                hair: document.getElementById('avatar-hair').value,
                eyes: document.getElementById('avatar-eyes').value
            };

            // Hide avatar modal
            hideAvatarModal();

            // Submit the quiz form with avatar data
            submitQuizWithAvatar(avatarData);
        });

        function submitQuizWithAvatar(avatarData) {
            // Submit quiz data via AJAX to generate Del Match Code
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Add answers
            Object.keys(answers).forEach(questionId => {
                formData.append(`answers[${questionId}]`, answers[questionId]);
            });

            // Add avatar data
            Object.keys(avatarData).forEach(key => {
                formData.append(`avatar[${key}]`, avatarData[key]);
            });

            fetch('{{ route("signup.submit") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide quiz section and show registration form
                    document.getElementById('app').style.display = 'none';
                    document.getElementById('registration-section').classList.remove('hidden');
                    
                    // Display the generated Del Match Code
                    document.getElementById('generated-code').textContent = data.del_match_code;
                } else {
                    alert('Error generating Del Match Code. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting quiz. Please try again.');
            });
        }

        // Original form submission (fallback)
        document.getElementById('signup-quiz-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // This will be handled by the modal flow instead
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' && !nextBtn.disabled) {
                nextQuestion();
                const hasErrors = '{{ $errors->any() ? "true" : "false" }}' === 'true';
                const hasOldInput = '{{ old("first_name") }}' !== '' || '{{ old("email") }}' !== '';
                
                if (hasErrors || hasOldInput) {
                    // Show registration form directly if there are validation errors
                    document.getElementById('app').style.display = 'none';
                    document.getElementById('registration-section').classList.remove('hidden');
                    // Set a default Del Match Code if one exists in session
                    const sessionDelCode = '{{ session("signup_quiz_results.del_match_code") ?? "" }}';
                    if (sessionDelCode) {
                        document.getElementById('generated-code').textContent = sessionDelCode;
                    }
                } else {
                    // Initialize quiz normally
                    initQuiz();
                }
            }
        });
        </script>
    </x-slot>

    <x-slot name="styles">
        <style>
            .quiz-option {
                transition: all 0.2s ease;
                color: #374151 !important;
            }

            .quiz-option:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .quiz-option.selected {
                color: #374151 !important;
            }
        </style>
    </x-slot>
</x-layout>
