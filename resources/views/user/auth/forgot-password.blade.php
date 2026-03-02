<x-layout title="Forgot Password - DelWell">

  <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-200/80">
      <div>
        <h2 class="mt-6 text-center text-4xl font-extrabold text-[#3A3A3A] font-['Cormorant_Garamond']">Forgot Password?</h2>
        <p class="mt-2 text-center text-sm text-gray-600">No worries! Enter your email and we'll send you a reset link.</p>
      </div>

      @if(Session::has('success'))
        <div class="p-4 rounded-md text-sm bg-green-100 text-green-700 border border-green-200">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              {{ Session::get('success') }}
            </div>
          </div>
        </div>
      @endif

      @if(Session::has('error'))
        <div class="p-4 rounded-md text-sm bg-red-100 text-red-700 border border-red-200">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              {{ Session::get('error') }}
            </div>
          </div>
        </div>
      @endif

      <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="post" id="forgot-password-form">
        @csrf

        <div class="space-y-5">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              required
              value="{{ old('email') }}"
              class="appearance-none relative block w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-[#A3B18A] focus:ring-[#A3B18A]' }} placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-1 sm:text-sm"
              placeholder="Enter your email address"
            />
            @error('email')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            id="submit-btn"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#FFC09F] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFC09F]/80 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span id="btn-text">Send Reset Link</span>
            <span id="btn-loading" class="hidden">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Sending...
            </span>
          </button>
        </div>
      </form>

      <div class="text-center space-y-2">
        <p class="text-sm text-gray-600">
          Remember your password?
          <a href="{{ route('login') }}" class="font-medium text-[#A3B18A] hover:text-[#A3B18A]/80">Sign in</a>
        </p>
        <p class="text-sm text-gray-600">
          Don't have an account?
          <a href="{{ route('invite.show') }}" class="font-medium text-[#A3B18A] hover:text-[#A3B18A]/80">Create one</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('forgot-password-form').addEventListener('submit', function() {
      const submitBtn = document.getElementById('submit-btn');
      const btnText = document.getElementById('btn-text');
      const btnLoading = document.getElementById('btn-loading');
      
      submitBtn.disabled = true;
      btnText.classList.add('hidden');
      btnLoading.classList.remove('hidden');
    });
  </script>

</x-layout>
