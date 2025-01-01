<?php

namespace App\Filament\Resources\GuidebookResource\Pages;

use App\Filament\Resources\GuidebookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGuidebook extends CreateRecord
{
    // protected function afterCreate(): void
    // {
    //     // Arahkan pengguna ke halaman View Task
    //     $this->redirect(
    //         route('filament.resources.guidebooks.view', ['record' => $this->record->id])
    //     );
    // }
    protected static string $resource = GuidebookResource::class;
}
