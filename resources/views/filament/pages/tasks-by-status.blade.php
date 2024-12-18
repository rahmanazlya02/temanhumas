@php
    $columnSpan = [
        'sm' => 4,
        'md' => 6,
        'lg' => 6
    ];
@endphp

<!-- Parent Container -->
<div class="flex flex-col gap-4"> 
    <!-- Judul -->
    <h2 class="text-lg font-bold text-gray-800">{{ $heading }}</h2>

    <!-- Grid -->
    <div class="grid grid-cols-{{ $columnSpan['sm'] }} sm:grid-cols-{{ $columnSpan['sm'] }} md:grid-cols-{{ $columnSpan['md'] }} lg:grid-cols-{{ $columnSpan['lg'] }} gap-4">
        <div class="col-span-full flex gap-4 justify-around">
            @foreach ($statuses as $status)
                <div class="rounded-lg p-4 text-center flex-1" style="background-color: {{ $status['color'] }};">
                    <h1 class="text-4xl font-bold text-white">{{ $status['count'] }}</h1>
                    <p class="text-sm font-medium text-white">{{ $status['name'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
