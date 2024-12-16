<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        return parent::getTableQuery()
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id) // Proyek milik pengguna
                      ->orWhereHas('users', function ($query) use ($user) { // Proyek yang melibatkan pengguna
                          $query->where('users.id', $user->id);
                      });
            });
    }
}
