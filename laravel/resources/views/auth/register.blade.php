<script>
function togglePasswordVisibility() {
    var passwordField = document.getElementById('password_confirmation');
    var eyeOpen = document.getElementById('eye-open');
    var eyeSlash = document.getElementById('eye-slash');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeSlash.classList.remove('hidden');
    } else {
        passwordField.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeSlash.classList.add('hidden');
    }
}
</script>
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative w-full">

            <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
            <button type="button" onclick="togglePasswordVisibility()"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path id="eye-open" fill-rule="evenodd"
                            d="M10 3C4.5 3 1 9 1 9s3.5 6 9 6 9-6 9-6-3.5-6-9-6zm0 10.5a3.5 3.5 0 110-7 3.5 3.5 0 010 7z" clip-rule="evenodd"/>
                        <path id="eye-slash" class="hidden"
                            d="M3.4 2.4a1 1 0 011.4 0l12 12a1 1 0 01-1.4 1.4l-12-12a1 1 0 010-1.4zM10 4.5c4.4 0 7.5 3.6 7.9 4.5-.4.9-3.5 4.5-7.9 4.5-1.1 0-2.1-.3-3-.7l1.3-1.3a3.5 3.5 0 104.7-4.7L10 4.5z"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
