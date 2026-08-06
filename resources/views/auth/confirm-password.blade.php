<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[var(--bg)] px-4 py-8">
        <div class="card w-full max-w-xl">
            <div class="text-center mb-8">
                <h1 class="font-heading text-3xl font-bold text-[var(--primary)]">{{ __('Confirm Password') }}</h1>
                <p class="text-sm text-[var(--text-secondary)] mt-2">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6">
                    <x-primary-button>
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
