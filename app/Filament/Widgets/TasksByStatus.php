<?php

namespace App\Filament\Widgets;

use App\Models\TicketStatus;
use Filament\Widgets\Widget;

class TasksByStatus extends Widget
{
    protected static string $view = 'filament.pages.tasks-by-status';

    // Mengambil data status dan jumlah tiket
    protected function getViewData(): array
    {
        $statuses = TicketStatus::all();

        return [
            'statuses' => $statuses->map(function ($status) {
                return [
                    'name' => $status->name,
                    'count' => $status->ticket_count,
                    'color' => $status->color,
                ];
            }),
        ];
    }
}
