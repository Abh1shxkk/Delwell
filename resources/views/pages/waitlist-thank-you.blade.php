<x-layout title="DelWell - Thank You">
    <style>
        .responsive-container {
            min-height: calc(100vh - 200px);
            padding: 3rem 1rem;
        }
        .responsive-card {
            max-width: 32rem;
            width: 100%;
        }
        .responsive-title {
            font-size: 2.25rem;
        }
        
        @media (min-width: 640px) {
            .responsive-container {
                padding: 3rem 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .responsive-container {
                padding: 3rem 2rem;
            }
        }
    </style>

    <div class="responsive-container flex items-center justify-center">
        <div class="responsive-card text-center bg-white p-10 rounded-xl shadow-lg border border-gray-200/80">
            <h2 class="responsive-title font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Thank You</h2>
            <p class="text-lg text-gray-600 mb-8">
                Your application has been received. We personally review each one to ensure our community's integrity. You'll hear from us via email soon.
            </p>
            <a 
                href="{{ route('invite') }}" 
                class="inline-block bg-[#A3B18A] text-white font-bold py-3 px-8 rounded-full text-lg hover:bg-opacity-90 transition-opacity duration-300"
            >
                Return to Homepage
            </a>
        </div>
    </div>
</x-layout>
