<x-layout title="Signup - DelWell">

        <main class="flex-grow">
            <div class="container mx-auto px-4 py-8 max-w-2xl">
                <div class="content-card p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold mb-2">Join DelWell</h2>
                        <p class="text-light">Create your account and start your journey to conscious connection.</p>
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-light mb-2">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required value="{{ old('first_name') }}" 
                                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-light mb-2">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required value="{{ old('last_name') }}" 
                                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                            </div>
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-light mb-2">Username *</label>
                            <input type="text" id="username" name="username" required value="{{ old('username') }}" 
                                   class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition"
                                   placeholder="Choose a unique username">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-light mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" 
                                   class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-light mb-2">Password *</label>
                                <input type="password" id="password" name="password" required 
                                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                                <p class="text-xs text-light mt-1">Minimum 8 characters with letters and numbers</p>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-light mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required 
                                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" id="accepted_terms" name="accepted_terms" required value="1"
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-peach focus:ring-peach">
                                <label for="accepted_terms" class="text-sm text-light">
                                    I agree to the <a href="#" class="text-peach hover:underline">Terms of Service</a> 
                                    and <a href="#" class="text-peach hover:underline">Privacy Policy</a> *
                                </label>
                            </div>

                            <div class="flex items-start space-x-3">
                                <input type="checkbox" id="email_notifications" name="email_notifications" value="1" checked
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-peach focus:ring-peach">
                                <label for="email_notifications" class="text-sm text-light">
                                    I'd like to receive email notifications about matches and messages
                                </label>
                            </div>

                            <div class="flex items-start space-x-3">
                                <input type="checkbox" id="marketing_emails" name="marketing_emails" value="1"
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-peach focus:ring-peach">
                                <label for="marketing_emails" class="text-sm text-light">
                                    Send me tips and updates about DelWell features
                                </label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="block py-2 px-4 text-white bg-[#FFC09F] hover:bg-opacity-80 rounded-full transition-colors duration-300 text-center w-full">Create Account & Start Quiz</button>
                        </div>
                    </form>

                    <div class="text-center mt-8">
                        <p class="text-light text-sm">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-semibold text-peach hover:underline">Log In</a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registration-form');
            
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    document.getElementById('password_confirmation').classList.add('border-red-500');
                    alert('Passwords do not match');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    document.getElementById('password').classList.add('border-red-500');
                    alert('Password must be at least 8 characters long');
                    return false;
                }
            });

            // Real-time validation
            document.querySelectorAll('input, select').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500') && this.value.trim()) {
                        this.classList.remove('border-red-500');
                    }
                });
            });
        });
    </script>
    
    <script src="{{ asset('js/user-script.js') }}"></script>
    </x-layout>