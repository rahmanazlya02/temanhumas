<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\ButtonAction::make('backToList')
                ->label('Back to List Users')
                ->url(fn () => route('filament.resources.users.index'))
                ->color('secondary'), // Opsional: Anda bisa menyesuaikan warna tombol.
        ];
    }
}
