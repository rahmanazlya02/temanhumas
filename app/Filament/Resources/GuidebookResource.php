<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidebookResource\Pages;
use App\Filament\Resources\GuidebookResource\RelationManagers;
use App\Models\Guidebook;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuidebookResource extends Resource
{
    protected static ?string $model = Guidebook::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 6;

    protected static function getNavigationGroup(): ?string
    {
        return __('Guideline');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('file')
                    ->label('Upload File')
                    ->disk('public') // Pastikan storage 'public' sudah disiapkan
                    ->directory('guidebooks') // Folder tempat file akan disimpan
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->wrap(),
                Tables\Columns\TextColumn::make('file')
                    ->label('File')
                    ->wrap()
                    ->url(fn($record) => asset('storage/' . $record->file)), // Link ke fil
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
