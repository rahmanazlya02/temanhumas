<?php

namespace App\Filament\Widgets;

use App\Models\TicketStatus;
use Filament\Widgets\Widget;

class TasksByStatus extends Widget
{
    protected static string $view = 'filament.pages.tasks-by-status';

    protected function getViewData(): array
    {
        $user = auth()->user(); 

        $statuses = TicketStatus::withCount(['tickets' => function ($query) use ($user) {
            $query->whereNull('deleted_at'); 
            if ($user->hasRole('Ketua Tim Humas')) {
                return;
            } elseif ($user->hasRole('Koordinator Subtim')) {
                $query->where(function ($query) use ($user) {
                    $query->where('responsible_id', $user->id)
                        ->orWhere('owner_id', $user->id)
                        ->orWhereHas('project', function ($query) use ($user) {
                            $query->where('owner_id', $user->id)
                                ->orWhereHas('users', function ($query) use ($user) {
                                    $query->where('users.id', $user->id);
                                });
                        });
                });
            } elseif ($user->hasRole('Anggota')) {
                $query->where('responsible_id', $user->id);
            }
        }])->get();

        return [
            'heading' => __('Tasks by Status'),
            'statuses' => $statuses->map(function ($status) {
                return [
                    'id' => $status->id,
                    'name' => $status->name,
                    'count' => $status->tickets_count,
                    'color' => $status->color,
                ];
            }),
        ];
    }
}
