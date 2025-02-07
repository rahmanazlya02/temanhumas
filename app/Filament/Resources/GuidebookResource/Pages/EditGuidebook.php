<?php

namespace App\Filament\Resources\GuidebookResource\Pages;

use App\Filament\Resources\GuidebookResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuidebook extends EditRecord
{
    protected static string $resource = GuidebookResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function afterSave(): void
    // {
    //     // Arahkan pengguna ke halaman View Task
    //     $this->redirect(
    //         route('filament.resources.guidebooks.list', ['record' => $this->record->id])
    //     );
    // }
}
