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
                </div>
            </form>
        </div>


    <!-- Pause Modal -->
    <div id="pause-modal" class="fixed inset-0 bg-black/50 z-50 hidden" style="display: flex; align-items: center; justify-content: center;">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
{{ ... }}
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
                Take 3 slow breaths. On the first, notice your body. On the second, notice your heart. On the third, notice your mind.
            </p>

            <!-- Continue Button -->
            <button id="pause-continue-btn" class="btn-continue">
                Continue
            </button>
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

        // Pause modal elements
        const pauseModal = document.getElementById('pause-modal');
        const pauseTitle = document.getElementById('pause-title');
        const pauseMessage = document.getElementById('pause-message');
        const pauseContinueBtn = document.getElementById('pause-continue-btn');

        // Pause messages for different section transitions
        const pauseMessages = {
            'Attachment Style': {
                title: 'A Moment to Pause',
                message: 'Take 3 slow breaths. On the first, notice your body. On the second, notice your heart. On the third, notice your mind.'
            },
            'Family Imprint': {
                title: 'A Moment to Pause',
                message: 'Pause for a moment. Notice how you feel answering these questions—curious? cautious? excited?'
            },
            'Values Alignment': {
                title: 'A Moment to Pause',
                message: 'Close your eyes for 5 seconds. Imagine what it would feel like to be understood in a relationship.'
            },
            'Energy Style': {
                title: 'A Moment to Pause',
                message: 'Take a deep breath. Notice your energy right now—are you feeling focused? curious? ready for the next part?'
            },
            'Practical Compatibility': {
                title: 'A Moment to Pause',
                message: 'Before we continue, take a moment to center yourself. You\'re doing great—these questions help us understand you better.'
            }
        };

        // Initialize quiz
        function initQuiz() {
            totalQuestionsEl.textContent = questions.length;
            if (questions.length > 0) {
                currentSection = questions[0].section;
            }
            showQuestion(0);
        }

        // Show specific question (similar logic to original quiz)
        function showQuestion(index) {
            if (index < 0 || index >= questions.length) return;

            const question = questions[index];

            // Check if we're changing sections and need to show a pause
            if (currentSection && currentSection !== question.section && index > currentQuestionIndex && !isHandlingSectionChange) {
                isHandlingSectionChange = true;
                pendingQuestionIndex = index;
                showPauseModal(question.section);
                return;
            }

            if (isHandlingSectionChange) {
                isHandlingSectionChange = false;
            }

            currentSection = question.section;
            currentQuestionIndex = index;

            currentQuestionEl.textContent = index + 1;
            progressBar.style.width = `${((index + 1) / questions.length) * 100}%`;
            currentSectionEl.textContent = question.section;
            questionText.textContent = question.question;

            updateSectionIndicators(question.section);
            generateOptions(question);
            updateNavigationButtons();
        }

        // Show pause modal
        function showPauseModal(nextSection) {
            const pauseData = pauseMessages[nextSection];
            if (pauseData) {
                pauseTitle.textContent = pauseData.title;
                pauseMessage.textContent = pauseData.message;
                pauseModal.classList.remove('hidden');
            } else {
                proceedToPendingQuestion();
            }
        }

        // Hide pause modal and proceed to pending question
        function proceedToPendingQuestion() {
            pauseModal.classList.add('hidden');
            if (pendingQuestionIndex !== null) {
                const targetIndex = pendingQuestionIndex;
                pendingQuestionIndex = null;
                showQuestion(targetIndex);
            }
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
            answers[questionId] = value;

            document.querySelectorAll('.quiz-option').forEach(btn => {
                btn.classList.remove('selected', 'border-peach', 'bg-peach', 'text-white');
                btn.classList.add('border-gray-200');
            });

            buttonEl.classList.add('selected', 'border-peach', 'bg-peach');
            buttonEl.classList.remove('border-gray-200');

            updateNavigationButtons();

            setTimeout(() => {
                if (currentQuestionIndex < questions.length - 1) {
                    nextQuestion();
                }
            }, 500);
        }

        // Update section indicators
        function updateSectionIndicators(currentSection) {
            sectionIndicators.forEach(indicator => {
                const dot = indicator.querySelector('div');
                const sectionName = indicator.dataset.section;

                if (sectionName === currentSection) {
                    indicator.classList.add('active');
                    dot.classList.remove('bg-gray-300');
                    dot.classList.add('bg-matcha');
                } else {
                    const sectionQuestions = questions.filter(q => q.section === sectionName);
                    const answeredQuestions = sectionQuestions.filter(q => answers[q.id]);

                    if (answeredQuestions.length === sectionQuestions.length) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-green-500');
                    }

                    indicator.classList.remove('active');
                }
            });
        }

        // Update navigation buttons
        function updateNavigationButtons() {
            const currentQuestion = questions[currentQuestionIndex];
            const hasAnswer = answers[currentQuestion.id];

            prevBtn.disabled = currentQuestionIndex === 0;

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

        if (pauseContinueBtn) {
            pauseContinueBtn.addEventListener('click', function(e) {
                e.preventDefault();
                proceedToPendingQuestion();
            });
        }

        // Form submission
        document.getElementById('continue-quiz-form').addEventListener('submit', function(e) {
            e.preventDefault();

            Object.keys(answers).forEach(questionId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${questionId}]`;
                input.value = answers[questionId];
                this.appendChild(input);
            });

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

            .section-indicator {
                text-align: center;
                opacity: 0.6;
                transition: opacity 0.3s;
            }
            /* Pause Modal Styles */
            #pause-modal {
                backdrop-filter: blur(4px);
            }

            #pause-modal .bg-white {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border-radius: 16px;
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
