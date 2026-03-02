<x-layout title="DelWell - Continue Your Profile">
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

            <!-- Section Header -->
            <div class="text-center mb-8">
                <h3 id="section-name" class="text-2xl font-semibold text-center text-gray-700 mb-4 font-['Cormorant_Garamond']">
                    Loading section...
                </h3>
            </div>

            <!-- Deeper Quiz Form -->
            <form id="deeper-quiz-form" method="POST" action="{{ route('user.quiz.submit') }}">
                @csrf

                <!-- Question Container -->
                <div id="question-container" class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/80 text-center min-h-[400px] flex flex-col justify-center">

                    <!-- Question -->
                    <div class="mb-8">
                        <h2 id="question-text" class="text-2xl text-center font-semibold text-gray-800 mb-6">
                            Loading question...
                        </h2>
                    </div>

                    <!-- Options -->
                    <div id="options-container" class="space-y-4 w-full">
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
            let sectionCounters = {};

            // DOM elements
            const progressBar = document.getElementById('progress-bar');
            const questionText = document.getElementById('question-text');
            const sectionName = document.getElementById('section-name');
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
                
                // Update section name with counter
                const sectionKey = question.section;
                if (!sectionCounters[sectionKey]) {
                    sectionCounters[sectionKey] = Object.keys(sectionCounters).length + 2; // Start from 2 since Del Match Code is 1
                }
                
                const sectionNumber = sectionCounters[sectionKey];
                const sectionLetter = String.fromCharCode(96 + (getSectionQuestionNumber(question.section, index) + 1)); // a, b, c, d
                sectionName.textContent = `${sectionNumber}${sectionLetter}. ${question.section}`;
                
                // Update question text
                questionText.textContent = question.question;

                // Generate options
                generateOptions(question);

                // Update navigation buttons
                updateNavigationButtons();
            }

            // Get question number within a section
            function getSectionQuestionNumber(section, currentIndex) {
                let count = 0;
                for (let i = 0; i <= currentIndex; i++) {
                    if (questions[i].section === section) {
                        if (i === currentIndex) return count;
                        count++;
                    }
                }
                return count;
            }

            // Generate options for current question
            function generateOptions(question) {
                optionsContainer.innerHTML = '';

                question.options.forEach((option, index) => {
                    const letter = String.fromCharCode(65 + index); // A, B, C, D
                    const optionDiv = document.createElement('div');
                    optionDiv.classList.add('quiz-option', 'p-4', 'border', 'border-gray-200', 'rounded-lg', 'cursor-pointer', 'hover:bg-gray-50', 'transition-colors');
                    
                    // Get the option value (could be option.value, option.letter, or just the letter)
                    const optionValue = option.value || option.letter || letter;
                    
                    // Check if this option was previously selected
                    const isSelected = answers[question.id] === optionValue;
                    if (isSelected) {
                        optionDiv.classList.add('selected');
                    }

                    optionDiv.innerHTML = `
                        <div class="text-left">
                            ${letter}) ${option.text}
                        </div>
                    `;

                    optionDiv.addEventListener('click', () => selectOption(question.id, optionValue, optionDiv));
                    optionsContainer.appendChild(optionDiv);
                });
            }

            // Select option
            function selectOption(questionId, value, optionElement) {
                // Update visual selection
                optionsContainer.querySelectorAll('.quiz-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                optionElement.classList.add('selected');

                // Store answer
                answers[questionId] = value;

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
                const form = document.getElementById('deeper-quiz-form');
                
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
                background-color: #f9fafb !important;
                border-color: #d1d5db !important;
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