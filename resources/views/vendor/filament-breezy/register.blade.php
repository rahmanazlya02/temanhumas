<x-filament-breezy::auth-card action="register">
    <div class="w-full flex justify-center text-white">
        <x-filament::brand />

    </div>
    <h2 class="mb-0">
        <img src="{{ asset('img/temanhumas.png') }}" class="h-16 mx-auto mb-0">
    </h2>
    <div>
        <h2 class="font-bold tracking-tight text-center text-2xl text-white">
            {{ __('filament-breezy::default.registration.heading') }}
        </h2>
        <p class="mt-2 text-sm text-center text-white">
            {{ __('filament-breezy::default.or') }}
            <a class="text-primary-400 hover:text-white" href="{{ route('filament.auth.login') }}">
                {{ strtolower(__('filament::login.heading')) }}
            </a>
        </p>
    </div>

    {{ $this->form }}

    <x-filament::button type="submit" class="w-full">
        {{ __('filament-breezy::default.registration.submit.label') }}
    </x-filament::button>

    @if (config('filament-socialite.enabled'))
        <x-filament-socialite::buttons />
    @endif
</x-filament-breezy::auth-card>
