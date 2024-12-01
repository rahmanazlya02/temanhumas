@if (count($providers))
    <div class="relative flex items-center justify-center text-center">
        <div class="absolute border-t border-blue-200 w-full h-px"></div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach ($providers as $key => $provider)
            @if ($key === 'google')
                <x-filament::button color="secondary" :icon="$provider['icon'] ?? null" tag="a" :href="route('socialite.oauth.redirect', $key)"
                    style="background-color: red; color: white;">
                    {{ $provider['label'] }}
                </x-filament::button>
            @endif
        @endforeach
    </div>
@else
    <span></span>
@endif
