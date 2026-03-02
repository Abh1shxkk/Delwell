<x-layout title="Contact - DelWell">
    <div class="bg-white py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond']">
                    Contact Us
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    We'd love to hear from you. Get in touch with our team.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-[#F9F7F3] rounded-xl p-8">
                    <h2 class="text-2xl font-bold text-[#3A3A3A] mb-6">Send us a message</h2>
                    
                    <!-- Success Message -->
                    <div id="successMessage" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="successText"></span>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="errorText"></span>
                        </div>
                    </div>

                    <form id="contactForm" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                required
                            >
                            <span class="text-red-500 text-sm error-message" id="name-error"></span>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                required
                            >
                            <span class="text-red-500 text-sm error-message" id="email-error"></span>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                required
                            >
                            <span class="text-red-500 text-sm error-message" id="subject-error"></span>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                required
                            ></textarea>
                            <span class="text-red-500 text-sm error-message" id="message-error"></span>
                        </div>

                        <button 
                            type="submit"
                            id="submitBtn"
                            class="w-full bg-[#FFC09F] text-white font-bold py-3 px-6 rounded-full text-lg hover:opacity-90 transition-opacity duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span id="btnText">Send Message</span>
                            <span id="btnLoader" class="hidden">
                                <svg class="animate-spin inline-block w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-bold text-[#3A3A3A] mb-6">Get in touch</h2>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-[#FFC09F] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-[#3A3A3A]">Email</h3>
                                    <p class="text-gray-600">hello@delwell.com</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-[#A3B18A] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-[#3A3A3A]">Office</h3>
                                    <p class="text-gray-600">San Francisco, CA</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-[#FFC09F] rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-[#3A3A3A]">Response Time</h3>
                                    <p class="text-gray-600">Within 24 hours</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#F9F7F3] rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-[#3A3A3A] mb-3">Support</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            For questions about your account, technical issues, or general inquiries, simply reply to any email from us or use the contact form.
                        </p>
                        <p class="text-gray-600 text-sm">
                            <strong>Business Hours:</strong> Monday - Friday, 9 AM - 6 PM PST
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form JavaScript -->
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.getElementById('successMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            
            // Get form data
            const formData = new FormData(this);
            
            // Get CSRF token from meta tag or input
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                            document.querySelector('input[name="_token"]')?.value;
            
            console.log('Submitting form with CSRF token:', csrfToken ? 'Found' : 'Missing');
            
            // Submit form via AJAX
            fetch('{{ route("contact.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Server error response:', text);
                        throw new Error('Server returned ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    document.getElementById('successText').textContent = data.message;
                    document.getElementById('successMessage').classList.remove('hidden');
                    
                    // Reset form
                    document.getElementById('contactForm').reset();
                    
                    // Scroll to success message
                    document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + '-error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        });
                    } else {
                        document.getElementById('errorText').textContent = data.message || 'Something went wrong. Please try again.';
                        document.getElementById('errorMessage').classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('errorText').textContent = 'Network error: ' + error.message + '. Please check console for details.';
                document.getElementById('errorMessage').classList.remove('hidden');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            });
        });
    </script>
</x-layout>
