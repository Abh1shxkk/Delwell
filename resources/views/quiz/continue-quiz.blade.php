<x-layout title="DelWell - Complete Your Compatibility Profile">
    <x-slot name="styles">
        <style>
            body {
                background-color: #f9f7f3;
                min-height: 100vh;
            }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

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
                <h1 class="text-4xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Deeper Layers Quiz</h1>
                <p class="text-gray-500 mb-6">Answer these questions to build a stronger profile.</p>
                
                <!-- Progress Bar -->
                <div class="w-full">
                    <div class="bg-gray-200 rounded-full h-3">
                        <div id="progress-bar" class="bg-matcha h-3 rounded-full transition-all duration-300" style="width: 3%"></div>
                    </div>
                </div>
            </div>

            <!-- Continue Quiz Form -->
            <form id="continue-quiz-form" method="POST" action="{{ route('user.quiz.submit') }}">
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
                                Complete Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quiz data -->
    <script type="application/json" id="quiz-data">
        @json($questionsArray)
    </script>

    <script>
        // Quiz data and state
        const questions = JSON.parse(document.getElementById('quiz-data').textContent);
        let currentQuestionIndex = 0;
        let answers = {};

        // DOM elements
        const progressBar = document.getElementById('progress-bar');
        const questionText = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');

        // Initialize quiz
        function initQuiz() {
            showQuestion(0);
        }

        // Show specific question
        function showQuestion(index) {
            if (index < 0 || index >= questions.length) return;

            const question = questions[index];
            currentQuestionIndex = index;

            // Update progress bar
            progressBar.style.width = `${((index + 1) / questions.length) * 100}%`;
            
            // Update question text
            questionText.textContent = question.question;

            // Generate options
            generateOptions(question);

            // Update navigation buttons
            updateNavigationButtons();
        }

        // Generate options for current question
        function generateOptions(question) {
            optionsContainer.innerHTML = '';

            question.options.forEach((option, index) => {
                const letter = String.fromCharCode(65 + index); // A, B, C, D
                const optionDiv = document.createElement('div');
                optionDiv.classList.add('quiz-option', 'p-4', 'border', 'border-gray-200', 'rounded-lg', 'cursor-pointer', 'hover:bg-gray-50', 'transition-colors');
                
                // Check if this option was previously selected
                const isSelected = answers[question.id] === option.letter;
                if (isSelected) {
                    optionDiv.classList.add('selected', 'bg-blue-50', 'border-blue-300');
                }

                optionDiv.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center text-sm font-medium ${isSelected ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300'}">
                            ${letter})
                        </div>
                        <span class="text-left">${option.text}</span>
                    </div>
                `;

                optionDiv.addEventListener('click', () => selectOption(question.id, option.letter, optionDiv));
                optionsContainer.appendChild(optionDiv);
            });
        }

        // Select option
        function selectOption(questionId, letter, optionElement) {
            // Update visual selection
            optionsContainer.querySelectorAll('.quiz-option').forEach(opt => {
                opt.classList.remove('selected', 'bg-blue-50', 'border-blue-300');
                const circle = opt.querySelector('div > div');
                circle.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
                circle.classList.add('border-gray-300');
            });

            optionElement.classList.add('selected', 'bg-blue-50', 'border-blue-300');
            const circle = optionElement.querySelector('div > div');
            circle.classList.remove('border-gray-300');
            circle.classList.add('border-blue-500', 'bg-blue-500', 'text-white');

            // Store answer
            answers[questionId] = letter;

            // Update navigation buttons
            updateNavigationButtons();
        }

        // Update navigation buttons state
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

        // Submit quiz
        function submitQuiz() {
            const form = document.getElementById('continue-quiz-form');
            
            // Clear existing answer inputs
            form.querySelectorAll('input[name^="answers"]').forEach(input => input.remove());
            
            // Add current answers
            Object.entries(answers).forEach(([questionId, answer]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${questionId}]`;
                input.value = answer;
                form.appendChild(input);
            });
            
            form.submit();
        }

        // Event listeners for navigation buttons
        nextBtn.addEventListener('click', nextQuestion);
        prevBtn.addEventListener('click', previousQuestion);
        submitBtn.addEventListener('click', submitQuiz);

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' && !nextBtn.disabled && !nextBtn.classList.contains('hidden')) {
                nextQuestion();
            } else if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
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
                background-color: #eff6ff !important;
                border-color: #3b82f6 !important;
            }

            .matcha {
                background-color: #A3B18A;
            }

            .bg-matcha {
                background-color: #A3B18A;
            }
        </style>
    </x-slot>
</x-layout>