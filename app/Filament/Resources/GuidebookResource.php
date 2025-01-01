<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidebookResource\Pages;
use App\Models\Guidebook;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;

class GuidebookResource extends Resource
{
    protected static ?string $model = Guidebook::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationLabel(): string
    {
        return __('Guidebook');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Guideline');
    }

    protected static ?int $navigationSort = 6;

    /**
     * Override the navigation URL to point to Google Drive.
     */
    // public static function getNavigationUrl(): string
    // {
    //     return 'https://drive.google.com/drive/folders/1xEwbFYnNu8vyCsJGgUMzw7BAgUnyyJxZ?usp=drive_link';
    // }

    // public static function getNavigationShouldOpenInNewTab(): bool
    // {
    //     return true;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(1)
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->label(__('Judul'))
                                    ->maxLength(255)
                                    ->required(),

                                Forms\Components\TextInput::make('google_drive_link')
                                    ->label(__('Link Guidebook'))
                                    ->url()
                                    ->required(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Judul'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('google_drive_link')
                    ->label(__('Link Guidebook'))
                    ->url(fn($record) => $record->google_drive_link, true) // Menambahkan link yang dapat diklik
                    ->openUrlInNewTab(), // Membuka link di tab baru
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuidebooks::route('/'),
            'create' => Pages\CreateGuidebook::route('/create'),
            'edit' => Pages\EditGuidebook::route('/{record}/edit'),
        ];
    }
}
