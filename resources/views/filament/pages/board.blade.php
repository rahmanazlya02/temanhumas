<x-filament::page>

    <div class="w-full flex flex-col gap-10 justify-center items-center">
        <form wire:submit.prevent="search" class="lg:w-[50%] w-full">
            {{ $this->form }}
        </form>
    </div>

</x-filament::page>