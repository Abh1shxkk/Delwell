<x-layout title="Verify Your Email - DelWell">
    <x-slot name="styles">
        <style>
            .pulse-animation {
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            .email-icon {
                background: linear-gradient(135deg, #FFC09F, #A3B18A);
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-12 min-h-[calc(100vh-200px)] flex items-center justify-center">
        <div class="content-card max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 email-icon rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-white pulse-animation" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#3A3A3A] mb-2 font-['Cormorant_Garamond']">Check Your Email</h1>
                <p class="text-gray-600">We've sent a verification link to:</p>
                <p class="text-lg font-semibold text-[#A3B18A] mt-1">{{ Auth::guard('user')->user()->email }}</p>
            </div>

        <!-- Status Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('message'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

            <!-- Instructions -->
            <div class="text-center mb-8">
                <p class="text-gray-600 mb-4">Click the verification link in your email to activate your account and start exploring DelWell.</p>
                
                <div class="bg-[#A3B18A]/10 rounded-lg p-4 mb-6 border border-[#A3B18A]/20">
                    <h3 class="font-semibold text-[#3A3A3A] mb-3 font-['Cormorant_Garamond'] text-lg">What's Next?</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center">
                            <span class="w-5 h-5 bg-[#A3B18A] text-white rounded-full flex items-center justify-center text-xs mr-2">✓</span>
                            Check your email inbox
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 bg-[#FFC09F] text-white rounded-full flex items-center justify-center text-xs mr-2">2</span>
                            Click the verification link
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs mr-2">3</span>
                            Complete your DelWell profile
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 bg-gray-300 text-white rounded-full flex items-center justify-center text-xs mr-2">4</span>
                            Start finding conscious connections
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <!-- Resend Email Button -->
                <button 
                    onclick="resendVerification()" 
                    id="resendButton"
                    class="w-full bg-[#FFC09F] hover:bg-[#FFC09F]/90 text-white font-semibold py-3 px-4 rounded-full transition-colors duration-200"
                >
                    <span id="resendText">Resend Verification Email</span>
                    <span id="resendLoading" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>

                <!-- Check Spam Folder -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-3">Not seeing the email?</p>
                    <div class="text-xs text-gray-400 space-y-1">
                        <p>• Check your spam/junk folder</p>
                        <p>• Make sure {{ config('mail.from.address') }} isn't blocked</p>
                        <p>• Wait a few minutes for delivery</p>
                    </div>
                </div>

                <!-- Alternative Actions -->
                <div class="flex space-x-4 pt-4 border-t border-[#A3B18A]/20">
                    <a href="{{ route('user.profile-settings') }}" class="flex-1 text-center py-2 px-4 border border-[#A3B18A]/30 rounded-full text-[#3A3A3A] hover:bg-[#A3B18A]/10 transition-colors duration-200">
                        Update Email
                    </a>
                    <a href="{{ route('user.logout') }}" class="flex-1 text-center py-2 px-4 border border-[#A3B18A]/30 rounded-full text-[#3A3A3A] hover:bg-[#A3B18A]/10 transition-colors duration-200">
                        Log Out
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            async function resendVerification() {
            const button = document.getElementById('resendButton');
            const text = document.getElementById('resendText');
            const loading = document.getElementById('resendLoading');
            
            // Disable button and show loading
            button.disabled = true;
            text.classList.add('hidden');
            loading.classList.remove('hidden');
            
            try {
                const response = await fetch('{{ route("user.email-verification.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Show success message
                    showMessage('Verification email sent successfully!', 'success');
                    
                    // Start cooldown timer
                    startCooldown();
                } else {
                        showMessage(data.message || 'Failed to send email. Please try again.', 'error');
                        resetButton();
                    }
                } catch (error) {
                    showMessage('Network error. Please check your connection and try again.', 'error');
                    resetButton();
                }
            }
            
            function resetButton() {
                const button = document.getElementById('resendButton');
                const text = document.getElementById('resendText');
                const loading = document.getElementById('resendLoading');
                
                button.disabled = false;
                text.classList.remove('hidden');
                loading.classList.add('hidden');
            }
            
            function startCooldown() {
                const button = document.getElementById('resendButton');
                const text = document.getElementById('resendText');
                const loading = document.getElementById('resendLoading');
                
                loading.classList.add('hidden');
                text.classList.remove('hidden');
                
                let seconds = 60;
                const interval = setInterval(() => {
                    text.textContent = `Resend in ${seconds}s`;
                    seconds--;
                    
                    if (seconds < 0) {
                        clearInterval(interval);
                        text.textContent = 'Resend Verification Email';
                        button.disabled = false;
                    }
                }, 1000);
            }
            
            function showMessage(message, type) {
                // Create and show a temporary message
                const alertDiv = document.createElement('div');
                const colorClasses = type === 'success' 
                    ? 'bg-[#A3B18A]/10 border-[#A3B18A]/30 text-[#A3B18A]' 
                    : 'bg-red-50 border-red-200 text-red-800';
                
                alertDiv.className = `${colorClasses} border px-4 py-3 rounded-lg mb-6`;
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        ${message}
                    </div>
                `;
                
                const card = document.querySelector('.content-card');
                card.insertBefore(alertDiv, card.children[1]);
                
                // Remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        </script>
    </x-slot>

</x-layout>