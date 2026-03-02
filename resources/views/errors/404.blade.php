<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Page Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: 'Montserrat', sans-serif;
      }
    </style>
  </head>
  <body class="min-h-screen flex items-center justify-center" style="background-color: #F5F1E8;">
    
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-8 md:p-16 text-center shadow-[0_4px_40px_rgba(255,192,159,0.2)] transform hover:scale-[1.02] transition-transform duration-300">
          
          <!-- Animated 404 Number -->
          <div class="relative">
            <h1 class="text-8xl md:text-9xl font-black mb-6 animate-pulse" style="color: #ffc09f;">
              404
            </h1>
            <div class="absolute inset-0 text-8xl md:text-9xl font-black blur-2xl" style="color: rgba(255, 192, 159, 0.1);">
              404
            </div>
          </div>

          <!-- Icon -->
          <div class="mb-6 flex justify-center">
            <svg class="w-24 h-24 animate-bounce" style="color: #ffc09f;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          
          <!-- Heading -->
          <h2 class="text-2xl md:text-4xl font-bold mb-4" style="color: #333;">
            Oops! Page Not Found
          </h2>
          
          <!-- Description -->
          <p class="text-base md:text-lg text-gray-600 mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved. Let's get you back on track.
          </p>
          
          <!-- Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ url('/') }}" 
               class="group relative inline-flex items-center justify-center px-8 py-3 text-base font-semibold text-white rounded-full overflow-hidden transition-all duration-300 hover:shadow-[0_4px_20px_rgba(255,192,159,0.4)] hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2" 
               style="background-color: #ffc09f; --tw-ring-color: #ffc09f;">
              <span class="relative z-10 flex items-center gap-2">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back Home
              </span>
              <div class="absolute inset-0 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left" style="background-color: #ffb088;"></div>
            </a>
            
            <button onclick="window.history.back()" 
                    class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold bg-transparent border-2 rounded-full transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2"
                    style="color: #ffc09f; border-color: #ffc09f; --tw-ring-color: #ffc09f;">
              <span class="flex items-center gap-2">
                Previous Page
              </span>
            </button>
          </div>

          <!-- Decorative Elements -->
          <div class="mt-12 flex justify-center gap-2">
            <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: #ffc09f;"></div>
            <div class="w-2 h-2 rounded-full animate-pulse delay-75" style="background-color: #ffd4bb;"></div>
            <div class="w-2 h-2 rounded-full animate-pulse delay-150" style="background-color: #ffb088;"></div>
          </div>

        </div>

        <!-- Additional Help Text -->
        <div class="text-center mt-8">
          <p class="text-sm" style="color: rgba(51, 51, 51, 0.7);">
            Need help? 
            <a href="{{ url('/contact') }}" class="underline font-semibold transition-colors hover:opacity-80" style="color: #ffc09f;">
              Contact Support
            </a>
          </p>
        </div>

      </div>
    </div>

  </body>
</html>
