<div class="kanban-statuses">
    <div class="status-container"
         data-status="{{ $status['id'] }}"
         id="status-records-{{ $status['id'] }}"
         style="border-color: {{ $status['color'] }}66;">
         <span style="background-color: {{ $status['color'] }}; 
                  color: #fff; 
                  padding: 15px; 
                  border-radius: 3px; 
                  font-size: 20px; 
                  font-weight: bold; 
                  display: inline-block;">
         {{ $status['title'] }}
        </span>
        @foreach($this->getRecords()->where('status', $status['id']) as $record)
            @include('partials.kanban.record')
        @endforeach

        @if($status['add_ticket'])
            <a class="create-record hover:cursor-pointer"
            href="{{ route('filament.resources.tickets.create', ['project' => request()->get('project')]) }}">
                <x-heroicon-o-plus class="w-4 h-4" /> {{ __('Create Ticket') }}
            </a>
        @endif


    </div>
</div>
