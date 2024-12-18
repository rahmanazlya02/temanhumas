<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidebookResource\Pages;
use App\Filament\Resources\GuidebookResource\RelationManagers;
use App\Models\Guidebook;
use Filament\Forms;
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

    protected static function getNavigationGroup(): ?string
    {
        return __('Guidebook');
    }


    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
