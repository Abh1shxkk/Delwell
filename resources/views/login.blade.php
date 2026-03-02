<x-layout title="Login - DelWell">

  <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-200/80">
      <div>
        <h2 class="mt-6 text-center text-4xl font-extrabold text-[#3A3A3A] font-['Cormorant_Garamond']">Welcome Back</h2>
        <p class="mt-2 text-center text-sm text-gray-600">Sign in to continue your journey.</p>
      </div>

      @if(Session::has('success'))
        <div class="p-3 rounded-md text-sm bg-green-100 text-green-700 border border-green-200">{{ Session::get('success') }}</div>
      @endif
      @if(Session::has('error'))
        <div class="p-3 rounded-md text-sm bg-red-100 text-red-700 border border-red-200">{{ Session::get('error') }}</div>
      @endif

      <form class="mt-8 space-y-6" action="{{ route('authenticate') }}" method="post">
        @csrf

        <div class="space-y-5">
          <div>
            <input
              id="login"
              name="login"
              type="email"
              autocomplete="email"
              value="{{ old('login') }}"
              class="appearance-none relative block w-full px-3 py-2 mb-6 border {{ $errors->has('login') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-[#A3B18A] focus:ring-[#A3B18A]' }} placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-1 sm:text-sm"
              placeholder="Email address"
            />
            @error('login')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <div class="flex items-center justify-between mb-1">
              <label for="password" class="sr-only">Password</label>
              <a href="{{ route('password.request') }}" class="text-sm text-[#A3B18A] hover:text-[#A3B18A]/80 font-medium">
                Forgot your password?
              </a>
            </div>
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              class="appearance-none relative block w-full px-3 py-2 border {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-[#A3B18A] focus:ring-[#A3B18A]' }} placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-1 sm:text-sm"
              placeholder="Password"
            />
            @error('password')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#FFC09F] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFC09F]/80 transition-colors">
            Sign in
          </button>
        </div>
      </form>

      <p class="mt-2 text-center text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('invite.show') }}" class="font-medium text-[#A3B18A] hover:text-[#A3B18A]/80">Start here</a>
      </p>
    </div>
  </div>

</x-layout>