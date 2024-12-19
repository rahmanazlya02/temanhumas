<?php

namespace App\Filament\Resources\GuidebookResource\Pages;

use App\Filament\Resources\GuidebookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuidebooks extends ListRecords
{
    protected static string $resource = GuidebookResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
