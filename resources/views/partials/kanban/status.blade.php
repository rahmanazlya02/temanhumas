<div class="kanban-statuses">
    <span class="status-header"
        style="background-color: {{ $status['color'] }}; 
                 color: #fff; 
                 padding: 15px; 
                 border-radius: 3px; 
                 font-size: 20px; 
                 font-weight: bold; 
                 display: inline-block;">
        {{ $status['title'] }}
    </span>
    <div class="status-container" data-status="{{ $status['id'] }}" id="status-records-{{ $status['id'] }}"
        style="border-color: {{ $status['color'] }}66;">
        @foreach ($this->getRecords()->where('status', $status['id']) as $record)
            @include('partials.kanban.record')
        @endforeach

        @if ($status['add_ticket'])
            <a class="create-record hover:cursor-pointer text-black font-bold hover:underline"
                href="{{ route('filament.resources.tickets.create', ['project' => request()->get('project')]) }}">
                <x-heroicon-o-plus class="w-4 h-4 md:w-6 md:h-6" />
                <span class="text-[12px] md:text-[14px]">{{ __('Create Task') }}</span>
            </a>
        @endif
    </div>
</div>
