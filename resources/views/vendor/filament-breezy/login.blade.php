@section('head')
    <style>
        .bg-custom-blue {
            background-color: #1E40AF;
            /* Warna biru yang lebih gelap */
        }
    </style>
@endsection

<x-filament-breezy::auth-card action="authenticate" class="bg-custom-blue">

    <div class="w-full flex justify-center mb-0">
        <x-filament::brand />
    </div>

    <h2 class="mb-0">
        <img src="{{ asset('img/temanhumas.png') }}" class="h-16 mx-auto mb-0">
    </h2>

    @if (config('system.login_form.is_enabled'))
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full bg-blue-500">
            {{ __('filament::login.buttons.submit.label') }}
        </x-filament::button>

        <div class="text-center">
            <a class="text-primary-600 hover:text-primary-700"
                href="{{ route(config('filament-breezy.route_group_prefix') . 'password.request') }}">{{ __('filament-breezy::default.login.forgot_password_link') }}</a>
        </div>
    @endif

    @if (config('filament-socialite.enabled'))
        <x-filament-socialite::buttons />
    @endif
</x-filament-breezy::auth-card>
