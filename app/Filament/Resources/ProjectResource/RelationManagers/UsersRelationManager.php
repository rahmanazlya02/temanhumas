<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Ticket; // Pastikan model Ticket diimport
use App\Models\User; // Import model User
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'projectsAffected';

    public function mount(): void
    {
        parent::mount();

        // Ambil semua user dari ticket responsibles terkait dengan proyek ini
        $tickets = Ticket::where('project_id', $this->ownerRecord->id)->get();

        $usersFromTickets = $tickets->flatMap(function ($ticket) {
            return $ticket->responsible; // Pastikan ada relasi `responsibles` di Ticket
        });

        // Sinkronisasi user ke proyek
        $this->ownerRecord->users()->syncWithoutDetaching($usersFromTickets->pluck('id'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('User full name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('pivot.role')
                    ->label(__('User role'))
                    ->enum(config('system.projects.affectations.roles.list'))
                    ->colors(config('system.projects.affectations.roles.colors'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->default(fn () => config('system.projects.affectations.roles.default'))
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->form(fn (Tables\Actions\EditAction $action): array => [
                        Forms\Components\Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }

    protected function canDeleteAny(): bool
    {
        return false;
    }
}
