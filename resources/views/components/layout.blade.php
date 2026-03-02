<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DelWell - Conscious Connections' }}</title>
    <!-- Standalone Tailwind CSS - Privacy browser friendly -->
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
      body {
        font-family: 'Lato', sans-serif;
        background-color: #F9F7F3;
      }
      h1, h2, h3, h4, h5, h6 {
        font-family: 'Cormorant Garamond', serif;
      }
    </style>
    <link rel="stylesheet" href="{{ asset('css/user-style.css') }}?v={{ filemtime(public_path('css/user-style.css')) }}" />
    
    <!-- Additional styles can be passed via slot -->
    {{ $styles ?? '' }}
</head>
<body class="text-dark antialiased">
    <!-- App Container -->
    <div id="app" class="min-h-screen flex flex-col">
        
        @include('components.header')

        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('components.footer')
    </div>
    
    <!-- Default JavaScript -->
    <!-- <script src="{{ asset('js/user-script.js') }}"></script> -->
    
    <!-- Additional scripts can be passed via slot -->
    {{ $scripts ?? '' }}
</body>
</html>