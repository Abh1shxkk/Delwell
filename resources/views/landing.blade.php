<x-layout title="DelWell - Conscious Connections">
  <div class="bg-[#F9F7F3]">
  {{-- Hero Section --}}
  <section class="text-center bg-[#FFC09F]/20 py-20 md:py-32 px-4">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-5xl md:text-7xl font-bold text-[#3A3A3A] mb-4">Where Heart Meets Growth</h1>
      <p class="text-2xl md:text-3xl text-gray-700 max-w-3xl mx-auto mb-8">
        Welcome to DelWell™: Dating Reimagined. We combine the wisdom of traditional matchmaking with the possibilities of modern connection.
      </p>
      <a
        href="{{ route('invite.show') }}"
        class="inline-block bg-[#FFC09F] text-white font-bold py-3 px-8 rounded-full text-lg hover:opacity-90 transition-opacity duration-300 shadow-lg"
      >
        Begin Your Journey
      </a>
    </div>
  </section>

  {{-- Mission Section --}}
  <section class="py-16 md:py-24 bg-[#A3B18A]/20">
    <div class="max-w-4xl mx-auto px-4 text-center">
      <h2 class="text-4xl md:text-5xl font-bold text-[#3A3A3A] mb-6">Discover Yourself, Connect Authentically</h2>
      <p class="text-lg text-gray-600 mb-8">
        Our mission is to create a relationship self-discovery platform that prioritizes emotional alignment over superficial interactions. DelWell guides you through self-discovery first, involves your trusted community, and uses every interaction as a tool for growth.
      </p>
    </div>
  </section>

  {{-- Features Section --}}
  <section class="py-16 md:py-24">
    <div class="max-w-6xl mx-auto px-4">
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Feature Card 1 --}}
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
          <div class="w-12 h-12 bg-[#FFC09F] rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-[#3A3A3A] mb-3">The Del Match Code™</h3>
          <p class="text-gray-600">
            A quick, psychology-based questionnaire generates your unique 5-letter code, revealing your core relationship tendencies.
          </p>
        </div>

        {{-- Feature Card 2 --}}
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
          <div class="w-12 h-12 bg-[#FFC09F] rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-[#3A3A3A] mb-3">Deepening the Profile</h3>
          <p class="text-gray-600">
            Move beyond photos. Create short videos answering thoughtful prompts to share your authentic voice and vibe.
          </p>
        </div>

        {{-- Feature Card 3 --}}
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
          <div class="w-12 h-12 bg-[#FFC09F] rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-[#3A3A3A] mb-3">The DelWell Circle</h3>
          <p class="text-gray-600">
            Invite trusted friends and family to a private section of your profile to vouch for you and help review matches.
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- Admin Quotes Section --}}
  <x-admin-quotes />
  
  </div>
</x-layout>
