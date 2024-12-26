<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\ButtonAction::make('backToList')
                ->label('Back to List Roles')
                ->url(fn () => route('filament.resources.roles.index'))
                ->color('secondary'), // Opsional: Anda bisa menyesuaikan warna tombol.
        ];
    }
}
