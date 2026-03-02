@php
    $quotes = \App\Models\AdminQuote::getActiveQuotes();
@endphp

@if($quotes->count() > 0)
    <div class="admin-quotes-section py-16 bg-gradient-to-br from-[#F9F7F3] to-[#f5f3ef]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-[#3A3A3A] font-['Cormorant_Garamond'] mb-4">
                    Words of Wisdom
                </h2>
                <p class="text-lg text-gray-600">
                    Inspiration for your journey of self-discovery and connection
                </p>
            </div>

            <div class="grid md:grid-cols-{{ min($quotes->count(), 3) }} gap-8">
                @foreach($quotes as $quote)
                    <div class="quote-card bg-white rounded-xl p-8 shadow-lg border border-[#A3B18A]/20 hover:shadow-xl transition-shadow duration-300">
                        <div class="text-center">
                            <!-- Quote Icon -->
                            <div class="w-12 h-12 bg-gradient-to-r from-[#A3B18A] to-[#FFC09F] rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                </svg>
                            </div>

                            <!-- Quote Text -->
                            <blockquote class="text-lg md:text-xl text-[#3A3A3A] font-medium leading-relaxed mb-6 italic">
                                "{{ $quote->quote }}"
                            </blockquote>

                            <!-- Attribution -->
                            <div class="text-sm text-[#A3B18A] font-semibold">
                                — DelWell Team
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<style>
    .quote-card {
        position: relative;
        overflow: hidden;
    }
    
    .quote-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #A3B18A, #FFC09F);
    }
    
    .quote-card:hover {
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .admin-quotes-section .grid {
            grid-template-columns: 1fr;
        }
    }
</style>
