<x-user-layout title="Complete Your DelWell Match Code™">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold mb-2">Complete Your DelWell Match Code™</h2>
            <p class="text-light">First, let's get some basic info, then we'll discover your unique compatibility profile.</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-light">Question <span id="current-question">1</span> of <span id="total-questions">5</span></span>
                <span class="text-sm text-light">Progress</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progress-bar" class="bg-matcha h-2 rounded-full transition-all duration-300" style="width: 20%"></div>
            </div>
        </div>

        <!-- Quiz Form -->
        <form id="user-quiz-form" method="POST" action="{{ route('user.quiz.complete') }}">
            @csrf

            <!-- Profile Info Step -->
            <div id="profile-step" class="content-card p-8 md:p-12">
                <h3 class="text-xl font-semibold mb-6 text-center">Basic Profile Information</h3>
                
                <div class="max-w-2xl mx-auto space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="age" class="block text-sm font-medium text-light mb-2">Age *</label>
                            <input type="number" id="age" name="age" required min="18" max="100" 
                                   class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-light mb-2">Gender *</label>
                            <select id="gender" name="gender" required 
                                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition bg-white">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="interested_in" class="block text-sm font-medium text-light mb-2">I'm interested in *</label>
                        <select id="interested_in" name="interested_in" required 
                                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition bg-white">
                            <option value="">Select Preference</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="button" id="start-quiz-btn" class="btn-primary">Start DelWell Match Code™ Quiz</button>
                    </div>
                </div>
            </div>

            <!-- Question Container -->
            <div id="question-container" class="content-card p-8 md:p-12 text-center min-h-[400px] flex flex-col justify-center hidden">

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
                            Complete My Profile
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Pause Modal -->
    <div id="pause-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
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
    <div id="avatar-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
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
                <button type="submit" class="btn-primary w-full !mt-6">Generate My Avatar & Complete</button>
            </form>
        </div>
    </div>

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
            const form = document.getElementById('user-quiz-form');
            
            // Add answers to form data
            Object.keys(answers).forEach(questionId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${questionId}]`;
                input.value = answers[questionId];
                form.appendChild(input);
            });

            // Add avatar data to form
            Object.keys(avatarData).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `avatar[${key}]`;
                input.value = avatarData[key];
                form.appendChild(input);
            });

            // Submit form
            form.submit();
        }

        // Original form submission (fallback)
        document.getElementById('user-quiz-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // This will be handled by the modal flow instead
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' && !nextBtn.disabled) {
                nextQuestion();
            } else if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
                previousQuestion();
            }
        });

        // Handle profile step
        document.getElementById('start-quiz-btn').addEventListener('click', function() {
            // Validate profile fields
            const age = document.getElementById('age').value;
            const gender = document.getElementById('gender').value;
            const interestedIn = document.getElementById('interested_in').value;
            
            if (!age || !gender || !interestedIn) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (parseInt(age) < 18) {
                alert('You must be at least 18 years old');
                return;
            }
            
            // Hide profile step and show quiz
            document.getElementById('profile-step').classList.add('hidden');
            document.getElementById('question-container').classList.remove('hidden');
            
            // Initialize quiz
            initQuiz();
        });

        // Initialize quiz on page load (but don't start automatically)
        document.addEventListener('DOMContentLoaded', function() {
            // Quiz will be initialized when user clicks "Start Quiz" button
        });
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
    </style>
</x-user-layout>
