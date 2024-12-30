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
    public static function getNavigationUrl(): string
    {
        return 'https://drive.google.com/drive/folders/1xEwbFYnNu8vyCsJGgUMzw7BAgUnyyJxZ?usp=drive_link';
    }

    public static function getNavigationShouldOpenInNewTab(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([]);
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
