<x-layout title="DelWell">
    <style>
        .responsive-title {
            font-size: 2.25rem;
            /* text-4xl equivalent */
        }

        .responsive-text {
            font-size: 1.125rem;
            /* text-lg equivalent */
        }

        .responsive-buttons {
            flex-direction: column;
        }

        .responsive-button {
            width: 100%;
        }

        @media (min-width: 640px) {
            .responsive-buttons {
                flex-direction: row;
            }

            .responsive-button {
                width: auto;
            }
        }

        @media (min-width: 768px) {
            .responsive-title {
                font-size: 3.75rem;
                /* text-6xl equivalent */
            }

            .responsive-text {
                font-size: 1.25rem;
                /* text-xl equivalent */
            }
        }
    </style>

    <div class="bg-[#F9F7F3] min-h-[calc(100vh-150px)] flex items-center justify-center text-center px-4 py-16">
        <div class="max-w-3xl mx-auto">
            <h1 class="responsive-title font-bold text-[#3A3A3A] mb-6 font-['Cormorant_Garamond']">
                The Waitlist for Intentional Dating is Open.
            </h1>
            <div class="responsive-text text-gray-700 max-w-3xl mx-auto mb-12 space-y-4">
                <p>
                    DelWell is a private, curated community for those committed to self-growth and meaningful connection.
                </p>
                <p>
                    Our members are vetted through a brief screening to ensure our community shares the same commitment to emotional health, respect, and readiness for a serious relationship.
                </p>
            </div>

            <div class="responsive-buttons flex justify-center items-center gap-4">
                <a href="{{ route('waitlist.apply') }}"
                    class="responsive-button bg-[#FFC09F] text-white font-bold py-3 px-8 rounded-full text-lg hover:opacity-90 transition-opacity duration-300 shadow-lg">
                    Apply to Join Waitlist
                </a>
                <a href="{{ route('invite-code') }}"
                    class="responsive-button bg-transparent border border-[#A3B18A] text-[#A3B18A] font-bold py-3 px-8 rounded-full text-lg hover:bg-[#A3B18A]/10 transition-colors">
                    Enter Invite Code
                </a>
            </div>
        </div>
    </div>
</x-layout>