<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Http\Controllers\WhatsappController;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getTitle(): string
    {
        return 'Edit Task';
    }

    protected function afterValidate(): void
    {
        // Dapatkan data task yang baru dibuat
        $task = $this->record;

        // Panggil controller untuk mengirimkan pesan
        app(WhatsappController::class)->updateMessage($task);

        // dd("Sukses");
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Arahkan pengguna ke halaman View Task
        $this->redirect(
            route('filament.resources.tickets.view', ['record' => $this->record->id])
        );
    }
}
