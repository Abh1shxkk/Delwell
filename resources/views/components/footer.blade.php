<!-- Footer -->
<footer class="bg-[#A3B18A]/20 text-[#3A3A3A]">
    <style>
        @media (min-width: 640px) {
            .footer-row {
                padding: 20px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
    </style>
        <div class="footer-row">
            <a href="{{ route('invite') }}" class="flex items-center mb-4 sm:mb-0 no-underline">
                <span class="brand-footer">DelWell™</span>
            </a>
            <ul class="footer-links mb-6 text-sm font-medium">
                <li><a href="{{ route('about') }}" class="hover:underline me-4 md:me-6">About</a></li>
                <li><a href="{{ route('privacy-policy') }}" class="hover:underline me-4 md:me-6">Privacy Policy</a></li>
                <li><a href="{{ route('licensing') }}" class="hover:underline me-4 md:me-6">Licensing</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />
        <span class="block text-sm text-center mb-8">© 2025<a href="{{ route('invite') }}" class="hover:underline no-underline">DelWell™</a>. All Rights Reserved.</span>
        <div class="mt-12 mb-8 text-xs text-gray-600 text-center space-y-6 max-w-5xl mx-auto px-4">
                <strong>Disclaimer:</strong> DelWell provides educational and personal growth content only. Our programs, assessments, and events are not medical, psychological, or therapeutic advice and do not create a therapist–client relationship or confidentiality protections. If you need clinical support, please contact a licensed provider. In an emergency, dial 911 (U.S.) or your local emergency number. DelWell assumes no liability for actions taken based on our content. We are committed to accessibility—if you experience barriers, please contact us at <a href="mailto:connect@hellodelwell.com" className="underline hover:text-gray-800">connect@hellodelwell.com</a>.
            </p>
            <p>
                <strong>Accessibility Matters:</strong> We are committed to making our website accessible to everyone and compliant with ADA standards. If you encounter any issues with accessibility or need assistance using our site, please email us at <a href="mailto:connect@hellodelwell.com" className="underline hover:text-gray-800">connect@hellodelwell.com</a>, and we’ll be glad to help.
            </p>
        </div>
    </div>
</footer>