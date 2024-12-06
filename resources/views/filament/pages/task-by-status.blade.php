<x-filament::page>
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-blue-500 text-white p-6 rounded-lg text-center shadow">
            <h2 class="text-3xl font-bold">{{ $toDo }}</h2>
            <p class="text-sm font-semibold uppercase">TO DO</p>
        </div>
        <div class="bg-orange-500 text-white p-6 rounded-lg text-center shadow">
            <h2 class="text-3xl font-bold">{{ $onProgress }}</h2>
            <p class="text-sm font-semibold uppercase">ON PROGRESS</p>
        </div>
        <div class="bg-green-500 text-white p-6 rounded-lg text-center shadow">
            <h2 class="text-3xl font-bold">{{ $done }}</h2>
            <p class="text-sm font-semibold uppercase">DONE</p>
        </div>
        <div class="bg-teal-600 text-white p-6 rounded-lg text-center shadow">
            <h2 class="text-3xl font-bold">{{ $approved }}</h2>
            <p class="text-sm font-semibold uppercase">APPROVED</p>
        </div>
    </div>
</x-filament::page>
