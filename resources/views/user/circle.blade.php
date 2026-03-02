<x-layout title="Your DelWell Circle - DelWell">
    <div class="bg-white min-h-screen">
        <div class="max-w-6xl mx-auto py-12 md:py-20 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-[#3A3A3A]">Your DelWell Circle</h1>
                <p class="text-lg text-gray-600 mt-2">Invite trusted friends and family to provide insights and support on your dating journey.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Invite Section -->
                <div class="space-y-6">
                    <!-- Invite New Member Card -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/50">
                        <h2 class="text-2xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Invite Someone to Your Circle</h2>
                        <p class="text-gray-600 mb-6">Add trusted people who know you well and can provide valuable insights about your relationships.</p>
                        
                        <form id="invite-form" class="space-y-4">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                    placeholder="Enter their full name">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent"
                                    placeholder="Enter their email address">
                            </div>
                            
                            <div>
                                <label for="relationship" class="block text-sm font-medium text-gray-700 mb-2">Relationship to You</label>
                                <select id="relationship" name="relationship" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#A3B18A] focus:border-transparent">
                                    <option value="">Select relationship</option>
                                    <option value="Best Friend">Best Friend</option>
                                    <option value="Close Friend">Close Friend</option>
                                    <option value="Family Member">Family Member</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Cousin">Cousin</option>
                                    <option value="Colleague">Colleague</option>
                                    <option value="Mentor">Mentor</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <button type="submit" id="invite-btn"
                                class="w-full bg-[#A3B18A] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-opacity">
                                <span class="btn-text">Send Invitation</span>
                                <span class="btn-loading hidden">
                                    <svg class="animate-spin h-4 w-4 text-white inline mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- How It Works Card -->
                    <div class="bg-gradient-to-r from-[#F9F7F3] to-[#FFF8F0] p-6 rounded-xl border border-gray-200/50">
                        <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">How Your Circle Helps</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-[#A3B18A] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">1</span>
                                </div>
                                <p class="text-gray-700 text-sm">They provide insights about your relationship patterns and preferences</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-[#FFC09F] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">2</span>
                                </div>
                                <p class="text-gray-700 text-sm">Their feedback helps improve your match recommendations</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-[#A3B18A] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">3</span>
                                </div>
                                <p class="text-gray-700 text-sm">You get support and guidance throughout your dating journey</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Current Circle -->
                <div class="space-y-6">
                    <!-- Current Circle Members -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/50">
                        <h2 class="text-2xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Your Circle Members</h2>
                        
                        @if(count($circleMembers) > 0)
                            <div class="space-y-4">
                                @foreach($circleMembers as $member)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-[#A3B18A] rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold">{{ substr($member['name'], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $member['name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $member['relationship'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Circle Members Yet</h3>
                                <p class="text-gray-500 text-sm mb-4">Start by inviting trusted friends and family members who know you well.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pending Invitations -->
                    @if(count($pendingInvitations) > 0)
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/50 mt-6">
                        <h2 class="text-2xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Pending Invitations</h2>
                        
                        <div class="space-y-3">
                            @foreach($pendingInvitations as $invitation)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $invitation->invitee_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $invitation->invitee_email }} • {{ $invitation->relationship }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Pending</span>
                                    <p class="text-xs text-gray-500 mt-1">Sent {{ $invitation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    </div>

                    <!-- Circle Insights (No Demo Data) -->
                    <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200/50">
                        <h2 class="text-2xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Circle Insights</h2>
                        
                        @if(count($circleRecommendations) > 0)
                            <div class="space-y-4">
                                @foreach($circleRecommendations as $recommendation)
                                <div class="bg-[#A3B18A]/10 p-4 rounded-lg border-l-4 border-[#A3B18A]">
                                    <p class="text-gray-700 italic">{{ $recommendation['insight'] }}</p>
                                    <p class="text-sm text-gray-500 mt-2">- {{ $recommendation['member_name'] }}</p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-700 mb-1">No Insights Yet</h3>
                                <p class="text-gray-500 text-sm">Once your circle members join, their insights will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('invite-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('invite-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            const formData = new FormData(this);
            
            fetch('{{ route("user.circle.invite") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    document.getElementById('invite-form').reset(); // Clear form
                } else {
                    alert('Error: ' + (data.message || 'Failed to send invitation'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to send invitation. Please try again. Check console for details.');
            })
            .finally(() => {
                // Reset button state
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            });
        });
    </script>
</x-layout>
