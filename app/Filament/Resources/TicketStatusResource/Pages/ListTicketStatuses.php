<?php

namespace App\Filament\Resources\TicketStatusResource\Pages;

use App\Filament\Resources\TicketStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTicketStatuses extends ListRecords
{
    protected static string $resource = TicketStatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Task Status'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereNull('project_id');
    }
}
