<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Task'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        return parent::getTableQuery()
            ->where(function ($query) use ($user) {
                $query->where('responsible_id', $user->id) // Tiket yang menjadi tanggung jawabnya
                    ->orWhere('owner_id', $user->id); // Tiket yang dia buat sendiri
                
                // Jika pengguna adalah ketua atau koordinator
                if ($user->hasRole(['Ketua Tim Humas', 'Koordinator Subtim'])) {
                    $query->orWhereHas('project', function ($projectQuery) use ($user) {
                        $projectQuery->where('owner_id', $user->id) // Proyek yang dimiliki oleh pengguna
                            ->orWhereHas('users', function ($usersQuery) use ($user) {
                                $usersQuery->where('users.id', $user->id); // Proyek yang dia terlibat
                            });
                    });
                }
            });
    }
}
