<x-layout title="User Discovery - DelWell">
    <div style="background-color: #F9F7F3; min-height: 100vh;">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-[#3A3A3A]">Discover Connections</h1>
                <p class="text-lg text-gray-600 mt-2">Here are your top 3 matches, including a special recommendation from your Circle.</p>
            </div>

            <!-- Profile Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if($hasCompletedFullQuiz && count($matches) > 0)
                    @foreach($matches as $match)
                        <!-- Profile Card - {{ $match['name'] }} -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200/80 transform hover:-translate-y-1 transition-transform duration-300 flex flex-col relative">
                            @if($match['is_circle_pick'])
                                <!-- Circle Pick Badge -->
                                <div style="position: absolute; top: 1rem; right: 1rem; background-color: #A3B18A; color: white; font-size: 0.75rem; font-weight: bold; padding: 0.25rem 0.75rem; border-radius: 9999px; z-index: 10; display: flex; align-items: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="height: 1rem; width: 1rem; margin-right: 0.375rem;" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Circle Pick
                                </div>
                            @endif
                            
                            @if($match['has_profile_image'])
                                <img class="w-full h-64 object-cover" src="{{ url('storage/' . $match['profile_image']) }}" alt="{{ $match['name'] }}" />
                            @else
                                <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                                    <x-profile-placeholder class="w-24 h-24 text-gray-400" />
                                </div>
                            @endif
                            
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-2xl font-bold font-['Cormorant_Garamond']">{{ $match['name'] }}, {{ $match['age'] }}</h3>
                                    <div class="text-right flex-shrink-0 ml-2">
                                        <p class="text-lg font-semibold text-[#A3B18A] tracking-wider">{{ $match['del_match_code'] }}</p>
                                        <p class="text-sm font-bold" style="color: #FFC09F;">{{ $match['match_percentage'] }}% Match</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 mt-2 mb-4">{{ $match['bio'] }}</p>
                                
                                <!-- Intro Video Section -->
                                <!-- @if($match['has_intro_video'])
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">Intro Video</h4>
                                    <div class="relative cursor-pointer group video-thumbnail" 
                                         data-video-url="{{ url('storage/' . $match['intro_video_path']) }}" 
                                         data-video-title="{{ $match['name'] }}'s Intro Video">
                                        <video class="rounded-lg w-full object-cover h-32" muted>
                                            <source src="{{ url('storage/' . $match['intro_video_path']) }}" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 bg-black/30 rounded-lg group-hover:bg-black/50 transition-colors"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="bg-white/90 rounded-full p-3 group-hover:bg-white transition-colors">
                                                <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex-grow"></div> -->

                                <div class="mt-4">
                                    <h4 class="font-semibold text-gray-700 mb-2 text-sm">How you align:</h4>
                                    <div style="background-color: rgba(163, 177, 138, 0.1); padding: 0.75rem; border-radius: 0.5rem; border-left: 4px solid #A3B18A;">
                                        <p class="text-sm text-gray-800" style="font-style: italic;">"{{ $match['alignment_description'] }}"</p>
                                    </div>
                                </div>
                                
                                <!-- Dynamic Match Actions -->
                                <div class="mt-6" id="match-actions-{{ $match['id'] }}">
                                    @if($match['is_mutual_match'])
                                        <!-- Mutual Match - Show Schedule Date -->
                                        <div class="text-center">
                                            <p class="font-bold text-lg text-[#A3B18A] mb-2">It's a Match!</p>
                                            <button data-action="schedule-date" data-user-id="{{ $match['id'] }}" 
                                                    class="w-full text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity" 
                                                    style="background-color: #A3B18A;">
                                                Schedule Date
                                            </button>
                                        </div>
                                    @elseif($match['match_status'] === 'pending')
                                        <!-- Already Flagged Interest -->
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600 mb-2">Interest flagged</p>
                                            <button class="w-full bg-neutral-400 text-white font-bold py-2 px-4 rounded-full cursor-not-allowed" disabled>
                                                Waiting for Response
                                            </button>
                                        </div>
                                    @else
                                        <!-- Flag Interest Button -->
                                        <button data-action="flag-interest" data-user-id="{{ $match['id'] }}" 
                                                class="w-full bg-[#FFC09F] text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-colors duration-300">
                                            Flag Interest
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif(!$hasCompletedFullQuiz)
                    <!-- Quiz completion prompt -->
                    <div class="col-span-full text-center py-12">
                        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Complete Your Profile</h3>
                            <p class="text-gray-600 mb-6">Finish your DelWell quiz to discover your perfect matches and unlock personalized recommendations.</p>
                            <a href="{{ route('user.continue-quiz') }}" class="inline-block bg-[#A3B18A] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-colors duration-300">
                                Complete Quiz
                            </a>
                        </div>
                    </div>
                @else
                    <!-- No matches available -->
                    <div class="col-span-full text-center py-12">
                        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">No Matches Yet</h3>
                            <p class="text-gray-600 mb-6">We're working on finding your perfect matches. Check back soon or adjust your preferences.</p>
                            <a href="{{ route('profile.complete') }}" class="inline-block bg-[#FFC09F] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-colors duration-300">
                                Update Preferences
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for Match Actions -->
    <script>
        // CSRF Token for AJAX requests
        const csrfToken = '{{ csrf_token() }}';

        // Event delegation for match actions and video thumbnails
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                const button = event.target;
                const action = button.getAttribute('data-action');
                const userId = button.getAttribute('data-user-id');
                
                if (action === 'flag-interest' && userId) {
                    flagInterest(userId, button);
                } else if (action === 'schedule-date' && userId) {
                    scheduleDate(userId, button);
                }
                
                // Handle video thumbnail clicks
                const videoThumbnail = event.target.closest('.video-thumbnail');
                if (videoThumbnail) {
                    event.preventDefault();
                    const videoUrl = videoThumbnail.getAttribute('data-video-url');
                    const videoTitle = videoThumbnail.getAttribute('data-video-title');
                    openVideoModal(videoUrl, videoTitle);
                }
            });
        });

        function flagInterest(userId, button) {
            const originalText = button.innerHTML;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = 'Flagging...';
            
            fetch('{{ route("user.flag-interest") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actionsDiv = document.getElementById(`match-actions-${userId}`);
                    
                    if (data.is_mutual_match) {
                        // It's a match! Show schedule date button
                        actionsDiv.innerHTML = `
                            <div class="text-center">
                                <p class="font-bold text-lg text-green-600 mb-2">It's a Match! 🎉</p>
                                <button data-action="schedule-date" data-user-id="${userId}" 
                                        class="w-full text-white font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition-opacity" 
                                        style="background-color: #A3B18A;">
                                    Schedule Date
                                </button>
                            </div>
                        `;
                        
                        // Show celebration animation
                        showMatchAnimation();
                    } else {
                        // Interest flagged, waiting for response
                        actionsDiv.innerHTML = `
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-2">Interest flagged</p>
                                <button class="w-full bg-neutral-400 text-white font-bold py-2 px-4 rounded-full cursor-not-allowed" disabled>
                                    Waiting for Response
                                </button>
                            </div>
                        `;
                    }
                } else {
                    alert(data.message || 'Failed to flag interest');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }

        function scheduleDate(userId, button) {
            const originalText = button.innerHTML;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = 'Scheduling...';
            
            fetch('{{ route("user.schedule-date") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'Failed to schedule date');
                }
                button.disabled = false;
                button.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }

        function showMatchAnimation() {
            // Simple celebration animation
            const celebration = document.createElement('div');
            celebration.innerHTML = '🎉 MATCH! 🎉';
            celebration.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(163, 177, 138, 0.95);
                color: white;
                padding: 20px 40px;
                border-radius: 20px;
                font-size: 24px;
                font-weight: bold;
                z-index: 9999;
                animation: bounce 0.6s ease-in-out;
            `;
            
            document.body.appendChild(celebration);
            
            setTimeout(() => {
                celebration.remove();
            }, 2000);
        }
        
        // Video Modal Functions
        function openVideoModal(videoUrl, title) {
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('modal-video');
            const titleEl = document.getElementById('modal-video-title');
            
            titleEl.textContent = title;
            video.src = videoUrl;
            modal.classList.remove('hidden');
        }

        function closeVideoModal() {
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('modal-video');
            
            modal.classList.add('hidden');
            video.pause();
            video.src = '';
        }

        // Close modal when clicking outside or on close button
        document.addEventListener('click', function(e) {
            if (e.target.id === 'video-modal' || e.target.closest('.video-modal-close')) {
                closeVideoModal();
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
            }
        });

        // Add bounce animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 20%, 60%, 100% { transform: translate(-50%, -50%) translateY(0); }
                40% { transform: translate(-50%, -50%) translateY(-30px); }
                80% { transform: translate(-50%, -50%) translateY(-15px); }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Video Lightbox Modal -->
    <div id="video-modal" class="fixed inset-0 bg-black/80 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6" style="background-color: #fff2ec;">
                    <h3 id="modal-video-title" class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] drop-shadow-sm">Video Title</h3>
                    <button class="video-modal-close text-white hover:text-gray-300 transition-colors p-2 rounded-full hover:bg-white/10">
                        <svg class="w-6 h-6 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Video -->
                <div class="relative bg-black">
                    <video id="modal-video" class="w-full max-h-[70vh] object-contain" controls autoplay>
                        Your browser does not support the video tag.
                    </video>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-4 bg-gray-50 flex justify-end space-x-3" style="background-color: #fff2ec;">
                    <button class="video-modal-close bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>