<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Http\Controllers\WhatsappController;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function afterCreate(): void
    {
        // Dapatkan data task yang baru dibuat
        $task = $this->record;

        // Panggil controller untuk mengirimkan pesan
        app(WhatsappController::class)->sendMessage($task);

        // dd("Sukses");
    }

    protected function getTitle(): string
    {
        return 'Create New Task';
    }
}
