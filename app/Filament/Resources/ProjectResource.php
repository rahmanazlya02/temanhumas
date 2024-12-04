<?php

namespace App\Filament\Resources;

use App\Exports\ProjectHoursExport;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Models\ProjectFavorite;
use App\Models\ProjectStatus;
use App\Models\Ticket;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationLabel(): string
    {
        return __('Projects');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(3)
                            ->schema([

                                Forms\Components\Grid::make()
                                    ->columnSpan(2)
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->columnSpan(2)
                                            ->columns(12)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('Project name'))
                                                    ->required()
                                                    ->columnSpan(10)
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\Select::make('owner_id')
                                            ->label(__('Project owner'))
                                            ->searchable()
                                            ->options(fn() => User::all()->pluck('name', 'id')->toArray())
                                            ->default(fn() => auth()->user()->id)
                                            ->required(),

                                        Forms\Components\Select::make('status_id')
                                            ->label(__('Project status'))
                                            ->searchable()
                                            ->options(fn() => ProjectStatus::all()->pluck('name', 'id')->toArray())
                                            ->default(fn() => ProjectStatus::where('is_default', true)->first()?->id)
                                            ->required(),
                                    ]),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('Project description'))
                                    ->columnSpan(3),

                                Forms\Components\Grid::make()
                                ->columns(3)
                                ->columnSpan(2)
                                ->schema([
                                    Forms\Components\DateTimePicker::make('deadline')
                                        ->label(__('Deadline'))
                                        ->required()
                                        ->reactive()
                                        ->minDate(now()->format('Y-m-d'))  // Today's date as the minimum date
                                        ->default(now()->setTime(0,0))  
                                ]),    
                            ]),
                            
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Project name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('Project owner'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label(__('Project status'))
                    ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="flex items-center gap-2">
                                <span class="filament-tables-color-column relative flex h-6 w-6 rounded-md"
                                    style="background-color: ' . $record->status->color . '"></span>
                                <span>' . $record->status->name . '</span>
                            </div>
                        '))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TagsColumn::make('users.name')
                    ->label(__('Users'))
                    ->limit(2),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('Deadline'))
                    ->dateTime() // Format sebagai tanggal
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('owner_id')
                    ->label(__('Owner'))
                    ->multiple()
                    ->options(fn() => User::all()->pluck('name', 'id')->toArray()),

                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->multiple()
                    ->options(fn() => ProjectStatus::all()->pluck('name', 'id')->toArray()),

                Tables\Filters\SelectFilter::make('deadline')
                    ->label('Deadline')
                    ->multiple()
                    ->options(fn() => Ticket::all()->pluck('deadline')->toArray()),
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('exportLogHours')
                        ->label(__('Export hours'))
                        ->icon('heroicon-o-document-download')
                        ->color('secondary')
                        ->action(fn($record) => Excel::download(
                            new ProjectHoursExport($record),
                            'time_' . Str::slug($record->name) . '.csv',
                            \Maatwebsite\Excel\Excel::CSV,
                            ['Content-Type' => 'text/csv']
                        )),

                    Tables\Actions\Action::make('kanban')
                        ->label(
                            fn ($record)
                                => ($record->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                        )
                        ->icon('heroicon-o-view-boards')
                        ->color('secondary')
                        ->url(function ($record) {
                            if ($record->type === 'scrum') {
                                return route('filament.pages.scrum/{project}', ['project' => $record->id]);
                            } else {
                                return route('filament.pages.kanban/{project}', ['project' => $record->id]);
                            }
                        }),
                ])->color('secondary'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SprintsRelationManager::class,
            RelationManagers\UsersRelationManager::class,
            RelationManagers\StatusesRelationManager::class,
            RelationManagers\TicketsRelationManager::class,
        ];
    }

public static function getPages(): array
{
    return [
        'index' => Pages\ListProjects::route('/'),
        'create' => Pages\CreateProject::route('/create'),
        'view' => Pages\ViewProject::route('/{record}', function ($record) {
            return [
                'components' => [
                    // Informasi Project
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label(__('Project\'s Name'))
                                ->default($record->name)
                                ->disabled(),

                            Forms\Components\TextInput::make('owner.name')
                                ->label(__('Project\'s Owner'))
                                ->default($record->owner->name)
                                ->disabled(),

                            Forms\Components\TextInput::make('status.name')
                                ->label(__('Project\'s Status'))
                                ->default($record->status->name)
                                ->disabled(),

                            Forms\Components\RichEditor::make('description')
                                ->label(__('Project\'s Description'))
                                ->default($record->description)
                                ->disabled(),
                        ])
                        ->columns(2), // Tampilkan dalam dua kolom untuk tata letak yang lebih baik

                    // Task List (Tickets Relation)
                    Forms\Components\Card::make()
                        ->schema([
                            Tables\Table::make()
                                ->columns(TicketsRelationManager::table(new Table)->getColumns()) // Ambil kolom dari TicketsRelationManager
                        ])
                        ->label(__('Task List')),

                    // Users List
                    Forms\Components\Card::make()
                        ->schema([
                            Tables\Table::make()
                                ->columns([
                                    Tables\Columns\TextColumn::make('name')
                                        ->label(__('User Full Name'))
                                        ->sortable()
                                        ->searchable(),
                                    Tables\Columns\TextColumn::make('role')
                                        ->label(__('User Role'))
                                        ->sortable()
                                        ->searchable(),
                                ])
                                ->rows(fn() => $record->users), // Ambil data pengguna terkait dari relasi
                        ])
                        ->label(__('Users')),
                ],
            ];
        }),
        'edit' => Pages\EditProject::route('/{record}/edit'),
    ];
}


}
