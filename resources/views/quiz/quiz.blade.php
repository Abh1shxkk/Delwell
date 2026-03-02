<x-layout title="DelWell Compatibility Quiz">
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
                <span class="text-sm text-light">Compatibility Quiz</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-light">
                    Question <span id="current-question">1</span> of <span id="total-questions">39</span>
                </div>
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-matcha h-2 rounded-full transition-all duration-300" style="width: 3%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">

            <!-- Quiz Form -->
            <form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}">
                @csrf

                <!-- Question Container -->
                <div id="question-container" class="content-card p-8 md:p-12 text-center min-h-[500px] flex flex-col justify-center">

                    <!-- Section Header -->
                    <div id="section-header" class="mb-6">
                        <div class="inline-block bg-matcha text-white px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <span id="current-section">Del Match Code™</span>
                        </div>
                    </div>

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
                                Complete Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Section Progress Indicator -->
            <div class="mt-8 bg-white rounded-lg p-6">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex space-x-4 overflow-x-auto">
                        <div class="section-indicator active" data-section="Del Match Code™">
                            <div class="w-3 h-3 rounded-full bg-matcha mb-1"></div>
                            <span class="whitespace-nowrap">Del Match Code™</span>
                        </div>
                        <div class="section-indicator" data-section="Attachment Style">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Attachment</span>
                        </div>
                        <div class="section-indicator" data-section="Family Imprint">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Family</span>
                        </div>
                        <div class="section-indicator" data-section="Values Alignment">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Values</span>
                        </div>
                        <div class="section-indicator" data-section="Energy Style">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Energy</span>
                        </div>
                        <div class="section-indicator" data-section="Practical Compatibility">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Practical</span>
                        </div>
                        <div class="section-indicator" data-section="Readiness Gate">
                            <div class="w-3 h-3 rounded-full bg-gray-300 mb-1"></div>
                            <span class="whitespace-nowrap">Readiness</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pause Modal -->
    <div id="pause-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
            <!-- Icon -->
            <div class="mx-auto w-16 h-16 bg-matcha rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>

            <!-- Title -->
            <h3 id="pause-title" class="text-xl font-semibold text-gray-800 mb-4">A Moment to Pause</h3>

            <!-- Message -->
            <p id="pause-message" class="text-gray-600 mb-6 leading-relaxed">
                Before you see your code, take a pause. Inhale for 4, hold for 4, exhale for 4, hold for 4. One round is enough.
            </p>

            <!-- Continue Button -->
            <button id="pause-continue-btn" class="btn-continue">
                Continue
            </button>
        </div>
    </div>

    <!-- Quiz data -->
    <script type="application/json" id="quiz-data">
        @json($questions)
    </script>

    <x-slot name="scripts">
        <script>
            // Quiz data and state
            const questions = JSON.parse(document.getElementById('quiz-data').textContent);
            let currentQuestionIndex = 0;
            let answers = {};
            let currentSection = '';
            let pendingQuestionIndex = null;
            let isHandlingSectionChange = false;

            // DOM elements
            const currentQuestionEl = document.getElementById('current-question');
            const totalQuestionsEl = document.getElementById('total-questions');
            const progressBar = document.getElementById('progress-bar');
            const currentSectionEl = document.getElementById('current-section');
            const questionText = document.getElementById('question-text');
            const optionsContainer = document.getElementById('options-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const sectionIndicators = document.querySelectorAll('.section-indicator');

            // Pause modal elements
            const pauseModal = document.getElementById('pause-modal');
            const pauseTitle = document.getElementById('pause-title');
            const pauseMessage = document.getElementById('pause-message');
            const pauseContinueBtn = document.getElementById('pause-continue-btn');

            // Initialize quiz
            function initQuiz() {
                totalQuestionsEl.textContent = questions.length;
                if (questions.length > 0) {
                    currentSection = questions[0].section;
                }
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
                currentSectionEl.textContent = question.section;
                questionText.textContent = question.question;

                // Generate options
                generateOptions(question);

                // Update navigation buttons
                updateNavigationButtons();

                // Update section indicators
                updateSectionIndicators(question.section);
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

                // Auto-advance after selection
                setTimeout(() => {
                    if (currentQuestionIndex < questions.length - 1) {
                        nextQuestion();
                    }
                }, 500);
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

            // Update section indicators
            function updateSectionIndicators(currentSectionName) {
                sectionIndicators.forEach(indicator => {
                    const sectionName = indicator.getAttribute('data-section');
                    const dot = indicator.querySelector('div');
                    
                    if (sectionName === currentSectionName) {
                        indicator.classList.add('active');
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-matcha');
                    } else {
                        indicator.classList.remove('active');
                        dot.classList.remove('bg-matcha');
                        dot.classList.add('bg-gray-300');
                    }
                });
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

            // Form submission
            document.getElementById('quiz-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Add answers to form data
                Object.keys(answers).forEach(questionId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `answers[${questionId}]`;
                    input.value = answers[questionId];
                    this.appendChild(input);
                });

                // Submit form
                this.submit();
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' && !nextBtn.disabled) {
                    nextQuestion();
                } else if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
                    previousQuestion();
                }
            });

            // Initialize quiz on page load
            document.addEventListener('DOMContentLoaded', initQuiz);
        </script>

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

            .section-indicator {
                text-align: center;
                opacity: 0.6;
                transition: opacity 0.3s;
            }

            .section-indicator.active {
                opacity: 1;
            }

            .section-indicator span {
                font-size: 0.75rem;
            }

            .btn-continue {
                background-color: var(--peach);
                color: var(--dark-text);
                font-weight: 600;
                padding: 12px 24px;
                border-radius: 9999px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .btn-continue:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </x-slot>
</x-layout>
