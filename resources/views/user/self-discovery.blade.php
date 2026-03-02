<x-layout title="Self Discovery Library - DelWell">
    <div class="bg-white min-h-screen">
        <div class="max-w-6xl mx-auto py-12 md:py-20 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-[#3A3A3A]">Self Discovery Library</h1>
                <p class="text-lg text-gray-600 mt-2">Guidance for your journey, created by psychologists. Click a card to reveal more.</p>
            </div>

            <!-- Therapist Booking Section -->
            <div class="bg-gradient-to-r from-[#F9F7F3] to-[#FFF8F0] rounded-xl p-7 mb-10 border border-gray-200/50">
                <div class="text-center mb-7">
                    <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-3">Book Therapy Sessions</h2>
                    <p class="text-gray-600 text-lg">Professional guidance tailored to your journey</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-4xl mx-auto">
                    <!-- Dr. Ladi Boustani -->
                    <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200/50 text-center hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-[#A3B18A] rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-lg font-bold">LB</span>
                        </div>
                        <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-2">Dr. Ladi Boustani</h3>
                        <p class="text-gray-600 text-sm mb-4">Licensed Clinical Psychologist</p>
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-[#3A3A3A]">$500</span>
                            <span class="text-gray-600 text-sm">/50 minutes</span>
                        </div>
                        <button onclick="bookAppointment('Dr. Ladi Boustani', 500)" class="w-full bg-[#A3B18A] text-white font-bold py-3 px-4 rounded-full hover:bg-opacity-90 transition-opacity">
                            Book Session
                        </button>
                    </div>

                    <!-- Dr. Shadi Golizadeh -->
                    <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200/50 text-center hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-[#FFC09F] rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-lg font-bold">SG</span>
                        </div>
                        <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-2">Dr. Shadi Golizadeh</h3>
                        <p class="text-gray-600 text-sm mb-4">Licensed Clinical Psychologist</p>
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-[#3A3A3A]">$500</span>
                            <span class="text-gray-600 text-sm">/50 minutes</span>
                        </div>
                        <button onclick="bookAppointment('Dr. Shadi Golizadeh', 500)" class="w-full bg-[#FFC09F] text-white font-bold py-3 px-4 rounded-full hover:bg-opacity-90 transition-opacity">
                            Book Session
                        </button>
                    </div>

                    <!-- Both Doctors -->
                    <div class="bg-white rounded-xl p-5 shadow-lg border border-gray-200/50 text-center hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-gradient-to-r from-[#A3B18A] to-[#FFC09F] rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">Both</span>
                        </div>
                        <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-2">Joint Session</h3>
                        <p class="text-gray-600 text-sm mb-4">Both Doctors Together</p>
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-[#3A3A3A]">$750</span>
                            <span class="text-gray-600 text-sm">/50 minutes</span>
                        </div>
                        <button onclick="bookAppointment('Both Doctors', 750)" class="w-full bg-gradient-to-r from-[#A3B18A] to-[#FFC09F] text-white font-bold py-3 px-4 rounded-full hover:opacity-90 transition-opacity">
                            Book Joint Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Card 1: Navigating Relationships After Divorce -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(0)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-0">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Navigating Relationships After Divorce</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Rebuilding After Divorce</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Healing isn't about replacing what was lost — it's about rediscovering yourself.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Love again, but this time from a place of wholeness, not fear."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Join DelWell to explore mindful dating after divorce — with clarity and confidence.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Navigating Relationships After Divorce')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Navigating Co-Parenting -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(1)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-1">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Navigating Co-Parenting</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Co-Parenting with Compassion</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">It's not about winning — it's about raising emotionally healthy children.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Boundaries + empathy = balance."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Learn emotional communication and boundaries that work — on DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Navigating Co-Parenting')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: PTSD (Post-Traumatic Stress) -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(2)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-2">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">PTSD (Post-Traumatic Stress)</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Healing from the Inside Out</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">PTSD isn't a weakness — it's a nervous system doing its best to protect you.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Your story deserves safety, understanding, and gentle repair."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Explore trauma-informed tools and self-regulation practices on DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('PTSD (Post-Traumatic Stress)')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Depression -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(3)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-3">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Depression</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">When the World Feels Heavy</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Depression isn't laziness — it's disconnection from yourself and meaning.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Small steps toward connection can begin to lift the fog."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Find emotional insight, connection, and guided reflection with DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Depression')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Anxiety -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(4)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-4">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Anxiety</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">When Your Mind Won't Rest</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Anxiety is your body's call for safety, not control.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Ground, breathe, and return home to your body."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Discover mindfulness and relational tools for calm on DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Anxiety')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 6: IVF & Single Parenting -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(5)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-5">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">IVF & Single Parenting</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">🌸 Redefining Family on Your Terms</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Choosing IVF or single parenting takes courage — and heart.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"You're building love in your own authentic way."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Connect with stories, support, and self-discovery on DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('IVF & Single Parenting')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 7: NPD (Narcissistic Personality Dynamics) -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(6)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-6">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">NPD (Narcissistic Personality Dynamics)</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Healing After Narcissistic Relationships</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">You can reclaim your power, voice, and trust.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Awareness is your greatest protection — and your path to peace."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Learn about emotional boundaries and self-worth restoration with DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('NPD (Narcissistic Personality Dynamics)')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 8: Bipolar Disorder -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(7)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-7">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Bipolar Disorder</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">🌗 Finding Balance Beyond Extremes</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Bipolar isn't just mood swings — it's a story of sensitivity and resilience.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"With understanding and structure, stability is possible."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">💛 Explore emotional regulation and holistic care practices with DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Bipolar Disorder')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 9: Empty Nesting -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(8)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-8">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Empty Nesting</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">🏡 When Home Gets Quieter</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Empty nesting isn't an ending — it's a new beginning with yourself.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Rediscover your passions, purpose, and independence."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Begin your next chapter of self-discovery with DelWell.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Empty Nesting')">
                                    Explore
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 10: Attachment Style Assessment -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(9)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-9">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Attachment Style</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">💝 How You Connect</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Understanding your attachment style helps you build healthier relationships.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Your past doesn't define you, but it can guide you."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Discover your attachment patterns and learn to love securely.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Attachment Style Assessment')">
                                    Take Assessment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 11: Values Alignment -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(10)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-10">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Values Alignment</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">🧭 What Matters Most</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Align your relationships with your core values for deeper connection.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"When values align, love flows naturally."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Explore what drives you and find compatible partners.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Values Alignment Assessment')">
                                    Take Assessment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 12: Energy Style -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(11)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-11">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Energy Style</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">⚡ Your Natural Rhythm</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">Understanding your energy patterns helps you thrive in relationships.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Honor your energy, honor yourself."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Discover how you recharge and connect with others.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Energy Style Assessment')">
                                    Take Assessment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 13: Family Imprint -->
                <div class="group cursor-pointer" style="perspective: 1000px; height: 400px;" onclick="toggleCard(12)">
                    <div class="card-inner relative w-full h-full" style="transform-style: preserve-3d;" id="card-12">
                        <!-- Card Front -->
                        <div class="card-front absolute w-full h-full bg-[#F9F7F3] rounded-xl shadow-lg border border-gray-200/50 flex flex-col justify-center items-center text-center p-6" style="backface-visibility: hidden;">
                            <h2 class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">Family Imprint</h2>
                            <p class="mt-4 text-gray-500 group-hover:text-[#A3B18A] transition-colors">Click to reveal</p>
                        </div>
                        <!-- Card Back -->
                        <div class="card-back absolute w-full h-full bg-white rounded-xl shadow-lg border border-gray-200/50 p-6 flex flex-col text-center" style="backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="text-xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A]">🏠 Your Relationship Blueprint</h3>
                                <p class="text-gray-700 mt-1 leading-relaxed">How your family of origin shapes your relationship patterns and expectations.</p>
                                <p class="text-lg italic text-gray-800 mt-4">"Awareness of the past creates freedom for the future."</p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">Understand your family patterns and create healthier relationships.</p>
                                <button class="bg-[#FFC09F] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-sm" onclick="event.stopPropagation(); openModal('Family Imprint Assessment')">
                                    Take Assessment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center z-50 hidden" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="bg-white p-8 max-w-md w-full mx-4 relative" style="border-radius: 16px;">
            <button onclick="closeModal()" class="absolute text-gray-600" style="top: 24px; right: 24px; width: 32px; height: 32px; border-radius: 50%; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e5e7eb'; this.style.color='#374151';" onmouseout="this.style.backgroundColor='#f3f4f6'; this.style.color='#6b7280';" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="modal-content">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <style>
        .card-inner {
            transition: transform 1s cubic-bezier(0.4, 0.0, 0.2, 1);
        }
        .card-inner.flipped {
            transform: rotateY(180deg);
        }
    </style>

    <script>
        let flippedCard = null;
        let currentModalContent = null;
        let modalView = 'options';

        const topicContent = {
            'Navigating Relationships After Divorce': { title: 'Navigating Relationships After Divorce' },
            'Navigating Co-Parenting': { title: 'Navigating Co-Parenting' },
            'PTSD (Post-Traumatic Stress)': { title: 'PTSD (Post-Traumatic Stress)' },
            'Depression': { title: 'Depression' },
            'Anxiety': { title: 'Anxiety' },
            'IVF & Single Parenting': { title: 'IVF & Single Parenting' },
            'NPD (Narcissistic Personality Dynamics)': { title: 'NPD (Narcissistic Personality Dynamics)' },
            'Bipolar Disorder': { title: 'Bipolar Disorder' },
            'Empty Nesting': { title: 'Empty Nesting' },
            'Attachment Style Assessment': { title: 'Attachment Style Assessment' },
            'Values Alignment Assessment': { title: 'Values Alignment Assessment' },
            'Energy Style Assessment': { title: 'Energy Style Assessment' },
            'Family Imprint Assessment': { title: 'Family Imprint Assessment' }
        };

        function toggleCard(index) {
            // Prevent flipping if a modal is open
            if (currentModalContent) return;
            
            const cardInner = document.getElementById(`card-${index}`);
            
            if (flippedCard === index) {
                // Flip back to front
                cardInner.classList.remove('flipped');
                flippedCard = null;
            } else {
                // Flip all cards back first
                for (let i = 0; i < 13; i++) {
                    const card = document.getElementById(`card-${i}`);
                    if (card) card.classList.remove('flipped');
                }
                
                // Flip the clicked card
                cardInner.classList.add('flipped');
                flippedCard = index;
            }
        }

        function openModal(topic) {
            currentModalContent = topicContent[topic];
            modalView = 'options';
            updateModalContent();
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            currentModalContent = null;
            modalView = 'options';
        }

        function setModalView(view) {
            modalView = view;
            updateModalContent();
        }

        function updateModalContent() {
            const modalContent = document.getElementById('modal-content');
            
            if (modalView === 'workshop') {
                modalContent.innerHTML = `
                    <button onclick="setModalView('options')" class="absolute top-4 left-4 text-gray-500 hover:text-gray-800 flex items-center text-sm font-medium" aria-label="Back to options">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </button>
                    <h2 id="modal-title" class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-2 mt-6">Live Workshop</h2>
                    <p class="text-gray-600 mb-4 text-lg">"${currentModalContent.title}"</p>
                    <div class="text-left bg-gray-50 p-4 rounded-lg border-l-4 border-[#A3B18A] my-6">
                        <p class="font-semibold text-gray-800">Led by Dr. Boustani</p>
                        <p class="text-gray-600"><strong>Date:</strong> November 15th, 7:00 PM EST</p>
                        <p class="text-sm text-gray-500 mt-2">Join us for an insightful session to explore this topic with expert guidance and a supportive community.</p>
                    </div>
                    <div class="space-y-4">
                        <button 
                            onclick="alert('You have RSVP\\'d for the in-person event. Details will be sent to your email.')"
                            class="w-full bg-[#A3B18A] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-base"
                        >
                            Join In-Person
                        </button>
                        <button 
                            onclick="alert('You have RSVP\\'d for the virtual event. A Zoom link will be sent to your email.')"
                            class="w-full bg-[#FFC09F] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-base"
                        >
                            Join via Zoom
                        </button>
                    </div>
                `;
            } else {
                modalContent.innerHTML = `
                    <h2 id="modal-title" class="text-3xl font-bold font-['Cormorant_Garamond'] text-[#3A3A3A] mb-4">Deepen Your Understanding</h2>
                    <p class="text-gray-600 mb-6">Take the next step in your journey on "${currentModalContent.title}."</p>
                    <div class="space-y-4">
                        <button 
                            onclick="setModalView('workshop')"
                            class="w-full bg-[#A3B18A] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-base"
                        >
                            Sign Up for a Workshop
                        </button>
                        <button 
                            onclick="alert('Opening coaching schedule...')"
                            class="w-full bg-[#FFC09F] text-white font-bold py-3 px-6 rounded-full hover:bg-opacity-90 transition-opacity text-base"
                        >
                            Book 1:1 Coaching
                        </button>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">Get daily reminders and insights.</p>
                            <button 
                                onclick="alert('Signing you up for daily reminders...')"
                                class="font-semibold text-[#A3B18A] hover:underline mt-1"
                            >
                                Learn more and sign up here
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        // Close modal when clicking outside
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('modal').classList.contains('hidden')) {
                closeModal();
            }
        });

        function bookAppointment(doctor, price) {
            let message = '';
            if (doctor === 'Both Doctors') {
                message = `You are booking a joint session with both Dr. Ladi Boustani and Dr. Shadi Golizadeh for $${price} per session. You will be redirected to the booking calendar.`;
            } else {
                message = `You are booking a session with ${doctor} for $${price} per 50 minutes. You will be redirected to the booking calendar.`;
            }
            
            if (confirm(message)) {
                // Here you can redirect to actual booking system
                alert('Redirecting to booking calendar... (This would integrate with your booking system)');
                // window.location.href = '/book-appointment?doctor=' + encodeURIComponent(doctor) + '&price=' + price;
            }
        }
    </script>
</x-layout>