<x-layout title="Enter Invite Code - DelWell">
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8 max-w-md">
            <div class="content-card p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-2">Welcome to DelWell</h2>
                    <p class="text-light">Enter your invitation code to get started</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('invite.verify') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="invite_code" class="block text-sm font-medium text-light mb-2">Invitation Code</label>
                        <input type="text" id="invite_code" name="invite_code" required value="{{ old('invite_code') }}" 
                               class="w-full p-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-peach focus:border-peach transition text-center text-lg tracking-wider uppercase"
                               placeholder="XXXXXXXX"
                               maxlength="32">
                        <p class="text-xs text-light mt-2">Enter the invitation code you received via email</p>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="block py-3 px-6 text-white bg-[#FFC09F] hover:bg-opacity-80 rounded-full transition-colors duration-300 text-center w-full font-semibold">
                            Continue to Registration
                        </button>
                    </div>
                </form>

                <div class="text-center mt-8">
                    <p class="text-light text-sm mb-2">
                        Don't have an invite code?
                    </p>
                    <a href="{{ route('waitlist.apply') }}" class="font-semibold text-peach hover:underline">
                        Join the Waitlist
                    </a>
                </div>

                <div class="text-center mt-4">
                    <p class="text-light text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold text-peach hover:underline">Log In</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto-uppercase the invite code
        document.getElementById('invite_code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    </script>
</x-layout>
