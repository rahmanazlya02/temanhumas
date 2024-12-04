<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets'; // Nama relasi yang menghubungkan project dengan ticket

    protected static ?string $recordTitleAttribute = 'title'; // Kolom yang digunakan untuk judul tiket

    protected static ?string $inverseRelationship = 'projects'; // Relasi terbalik (jika ada)

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Task name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('Owner'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('responsible.name')
                    ->label(__('Responsible'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label(__('Status'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type.name')
                    ->label(__('Type'))
                    ->formatStateUsing(
                        fn($record) => view('partials.filament.resources.ticket-type', ['state' => $record->type])
                    )
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('priority.name')
                    ->label(__('Priority'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->dateTime() // Format sebagai tanggal
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
            ]);
    }

    protected function canCreate(): bool
    {
        // Nonaktifkan pembuatan tiket
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        // Nonaktifkan penghapusan tiket
        return false;
    }

    protected function canDeleteAny(): bool
    {
        // Nonaktifkan penghapusan tiket secara massal
        return false;
    }
}