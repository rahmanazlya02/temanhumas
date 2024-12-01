<div class="inline-flex items-center space-x-2 rtl:space-x-reverse px-4">
    <div class="w-full flex items-center gap-2">
        @php($socials = $getState())
        @foreach ($socials as $social)
            @if ($social->provider === 'google')
                <x-icon name="fab-google" class="social-icon google" />
            @endif
        @endforeach
    </div>
</div>
