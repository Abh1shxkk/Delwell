<x-layout title="DelWell - Complete Your Registration">
    <x-slot name="styles">
        <style>
            body {
                background-color: #f9f7f3;
                min-height: 100vh;
            }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <!-- Quiz Header -->
    

    <div class="flex-grow flex items-center justify-center p-12">
        <div class="max-w-2xl w-full">

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Quiz Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Your Del Match Code™ Quiz</h1>
                <p class="text-gray-500 mb-6">Answer 5 questions to discover your relationship DNA.</p>
                
                <!-- Progress Bar -->
                <div class="w-full">
                    <div class="bg-gray-200 rounded-full h-3">
                        <div id="main-progress-bar" class="bg-matcha h-3 rounded-full transition-all duration-300" style="width: 20%"></div>
                    </div>
                </div>
            </div>

            <!-- Registration Quiz Form -->
            <form id="registration-quiz-form" method="POST" action="{{ route('registration.complete') }}">
                @csrf

                <!-- Question Container -->
                <div id="question-container" class="bg-white rounded-lg shadow-lg border border-gray-100 p-8 md:p-12 text-center min-h-[400px] flex flex-col justify-center">

                    <!-- Question -->
                    <div class="mb-8">
                        <h2 id="question-text" class="text-2xl text-center font-semibold text-gray-800 mb-6">
                            Loading question...
                        </h2>
                    </div>

                    <!-- Options -->
                    <div id="options-container" class="space-y-4 max-w-2xl mx-auto">
                        <!-- Options will be dynamically inserted here -->
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-12 pt-8 border-t border-gray-200">
                        <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            ← Previous
                        </button>

                        <div class="flex space-x-2">
                            <button type="button" id="next-btn" class="px-6 py-3 bg-[#A3B18A] text-white rounded-lg hover:bg-[#8a9a75] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Next Question →
                            </button>
                            <button type="button" id="submit-btn" class="px-6 py-3 bg-[#A3B18A] text-white rounded-lg hover:bg-[#8a9a75] transition-colors hidden">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals removed - direct submission now used -->

    <!-- Quiz data -->
    <script type="application/json" id="quiz-data">
        @json($questions)
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
        const mainProgressBar = document.getElementById('main-progress-bar');
        const questionText = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');

        console.log('Main progress bar element:', mainProgressBar);

        // Initialize quiz
        function initQuiz() {
            if (totalQuestionsEl) {
                totalQuestionsEl.textContent = questions.length;
            }
            showQuestion(0);
        }

        // Show specific question
        function showQuestion(index) {
            if (index < 0 || index >= questions.length) return;

            const question = questions[index];
            currentQuestionIndex = index;

            // Update UI elements (only if elements exist)
            if (currentQuestionEl) {
                currentQuestionEl.textContent = index + 1;
            }
            
            if (progressBar) {
                progressBar.style.width = `${((index + 1) / questions.length) * 100}%`;
            }
            
            // Update main progress bar if it exists
            if (mainProgressBar) {
                mainProgressBar.style.width = `${((index + 1) / questions.length) * 100}%`;
                console.log('Updated main progress bar to:', `${((index + 1) / questions.length) * 100}%`);
            } else {
                console.log('Main progress bar element not found');
            }
            
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

            // Update navigation buttons
            updateNavigationButtons();
        }

        // Update navigation buttons
        function updateNavigationButtons() {
            const currentQuestion = questions[currentQuestionIndex];
            const hasAnswer = answers[currentQuestion.id];

            // Previous button (only if it exists)
            if (prevBtn) {
                prevBtn.disabled = currentQuestionIndex === 0;
            }

            // Next/Submit button (only if they exist)
            if (currentQuestionIndex === questions.length - 1) {
                if (nextBtn) nextBtn.classList.add('hidden');
                if (submitBtn) {
                    submitBtn.classList.remove('hidden');
                    submitBtn.disabled = !hasAnswer;
                }
            } else {
                if (nextBtn) {
                    nextBtn.classList.remove('hidden');
                    nextBtn.disabled = !hasAnswer;
                }
                if (submitBtn) submitBtn.classList.add('hidden');
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

        // Event listeners (only if buttons exist)
        if (nextBtn) nextBtn.addEventListener('click', nextQuestion);
        if (prevBtn) prevBtn.addEventListener('click', previousQuestion);
        if (submitBtn) submitBtn.addEventListener('click', submitQuizDirectly);

        // Direct form submission function
        function submitQuizDirectly() {
            const form = document.getElementById('registration-quiz-form');

            // Add answers to form data
            Object.keys(answers).forEach(questionId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${questionId}]`;
                input.value = answers[questionId];
                form.appendChild(input);
            });

            // Submit form directly to results page
            form.submit();
        }

        // Modal functions (keeping for compatibility but not used)
        function showPauseModal() {
            document.getElementById('pause-modal').classList.remove('hidden');
        }

        function hidePauseModal() {
            document.getElementById('pause-modal').classList.add('hidden');
        }

        function showAvatarModal() {
            document.getElementById('avatar-modal').classList.remove('hidden');
        }

        function hideAvatarModal() {
            document.getElementById('avatar-modal').classList.add('hidden');
        }

        // Modal event listeners (removed - direct submission now used)

        // submitQuizWithAvatar function removed - now using direct submission

        // Original form submission (fallback)
        document.getElementById('registration-quiz-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // This will be handled by the modal flow instead
        });

        // Keyboard navigation (only if buttons exist)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' && nextBtn && !nextBtn.disabled) {
                nextQuestion();
            } else if (e.key === 'ArrowLeft' && prevBtn && !prevBtn.disabled) {
                previousQuestion();
            }
        });

        // Initialize quiz on page load
        document.addEventListener('DOMContentLoaded', initQuiz);
    </script>

    <x-slot name="scripts">
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