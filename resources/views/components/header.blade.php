<!-- Header -->
<nav class="sticky-header border-b border-[#A3B18A]/20">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('invite') }}" class="flex items-center space-x-3 rtl:space-x-reverse no-underline">
            <span class="brand">DelWell™</span>
        </a>

        <!-- Hamburger Menu Button (Hidden on large screens) -->
        <button id="hamburger-btn" type="button" style="display: inline-flex;" class="items-center p-2 w-10 h-10 justify-center text-sm rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 lg:hidden" aria-controls="navbar-default" aria-expanded="false">
            <span style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>

        <!-- Navigation Menu -->
        <div id="navbar-default" style="display: none;" class="w-full lg:w-auto lg:block">
            <ul class="font-medium flex flex-col lg:flex-row items-center gap-6 lg:gap-x-10 mt-4 lg:mt-0 p-4 lg:p-0 border border-gray-100 lg:border-0 rounded-lg bg-gray-50 lg:bg-transparent" style="list-style: none;">
                @if(Auth::guard('user')->check())
                    <li>
                        <a href="{{ route('user.dashboard') }}" data-page="dashboard" class="block py-2 {{ request()->routeIs('user.dashboard') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Dashboard</a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('user.discovery') }}" data-page="discovery" class="block py-2 text-[#3A3A3A] hover:text-[#A3B18A]">Discover</a>
                    </li> -->
                    <li>
                        <a href="{{ route('user.self-discovery') }}" data-page="self-discovery" class="block py-2 text-[#3A3A3A] hover:text-[#A3B18A]">Self Discovery</a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile-settings') }}" data-page="profile-settings" class="block py-2 {{ request()->routeIs('user.profile-settings') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Settings</a>
                    </li>
                    <li>
                        <a href="{{ route('user.logout') }}" class="block py-2 text-[#3A3A3A] hover:text-[#A3B18A]">Logout</a>
                    </li>
                @elseif(Auth::guard('manager')->check())
                    <li>
                        <a href="{{ route('manager.dashboard') }}" class="block py-2 {{ request()->routeIs('manager.dashboard') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('manager.logout') }}" class="block py-2 text-[#3A3A3A] hover:text-[#A3B18A]">Logout</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('features') }}" class="block py-2 {{ request()->routeIs('features') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Features</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="block py-2 {{ request()->routeIs('about') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">About</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="block py-2 {{ request()->routeIs('contact') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Contact</a>
                    </li>
                    <li>
                        <a href="{{ route('guest.self-discovery') }}" class="block py-2 {{ request()->routeIs('quiz.*') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Learn</a>
                    </li>
                    
                    @if(!request()->routeIs('register') && !request()->routeIs('quiz.*') && !request()->routeIs('user.email-verification.*') && !request()->routeIs('registration.*'))
                        <li>
                            <a href="{{ route('login') }}" class="block py-2 {{ request()->routeIs('login') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Login</a>
                        </li>
                        <li>
                            <a href="{{ route('invite.show') }}" class="block py-2 px-4 text-white bg-[#FFC09F] hover:bg-opacity-80 rounded-full transition-colors duration-300 text-center">Sign Up</a>
                        </li>
                    @elseif(request()->routeIs('login'))
                        <li>
                            <a href="{{ route('invite.show') }}" class="block py-2 px-4 text-white bg-[#FFC09F] hover:bg-opacity-80 rounded-full transition-colors duration-300 text-center">Sign Up</a>
                        </li>
                    @elseif(request()->routeIs('register') || request()->routeIs('quiz.*') || request()->routeIs('user.email-verification.*') || request()->routeIs('registration.*'))
                        <li>
                            <a href="{{ route('login') }}" class="block py-2 {{ request()->routeIs('login') ? 'text-[#A3B18A]' : 'text-[#3A3A3A]' }} hover:text-[#A3B18A]">Login</a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>

<script>
    (function () {
        var header = document.querySelector('.sticky-header');
        if (!header) return;
        
        // Sticky header functionality
        function updateHeader() {
            if (window.scrollY > 8) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });

        // Hamburger menu toggle
        var hamburgerBtn = document.getElementById('hamburger-btn');
        var navbar = document.getElementById('navbar-default');
        
        if (hamburgerBtn && navbar) {
            // Initialize menu visibility based on screen size
            function initMenuVisibility() {
                if (window.innerWidth >= 1024) {
                    // Desktop: always show
                    navbar.style.display = 'block';
                    hamburgerBtn.style.display = 'none';
                } else {
                    // Mobile/Tablet: hide menu, show hamburger
                    navbar.style.display = 'none';
                    hamburgerBtn.style.display = 'inline-flex';
                }
            }
            
            initMenuVisibility();
            
            // Toggle menu on hamburger click
            hamburgerBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (navbar.style.display === 'none') {
                    navbar.style.display = 'block';
                    hamburgerBtn.setAttribute('aria-expanded', 'true');
                } else {
                    navbar.style.display = 'none';
                    hamburgerBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                var isClickInsideNav = navbar.contains(event.target);
                var isClickOnHamburger = hamburgerBtn.contains(event.target);
                
                if (!isClickInsideNav && !isClickOnHamburger && navbar.style.display === 'block' && window.innerWidth < 1024) {
                    navbar.style.display = 'none';
                    hamburgerBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                initMenuVisibility();
            });
        }
    })();
</script>