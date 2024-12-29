<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidebookResource\Pages;
use App\Filament\Resources\GuidebookResource\RelationManagers;
use App\Models\Guidebook;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('file')
                            ->enableDownload()
                            ->preserveFilenames()
                            ->label('Guidebook')
                            ->directory('guidebooks') // Folder penyimpanan di storage/app/public/guidebooks
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi'),
                Tables\Columns\TextColumn::make('file')
                    ->label('Guidebook')
                    ->url(fn($record) => Storage::url($record->file)) // Agar file bisa diakses publik
                    ->openUrlInNewTab(), // Membuka file di tab baru
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
