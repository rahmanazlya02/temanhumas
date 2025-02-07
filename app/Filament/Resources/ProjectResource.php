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
                        // Grid utama
                        Forms\Components\Grid::make()
                            ->columns(['sm' => 1, 'lg' => 8]) // 1 kolom di mobile, 8 kolom di desktop
                            ->schema([

                                // Sub-grid untuk nama proyek
                                Forms\Components\Grid::make()
                                    ->columnSpan(['sm' => 12, 'lg' => 8]) // Responsif
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Project name'))
                                            ->required()
                                            ->columnSpan(['sm' => 12, 'lg' => 8]) // Per baris di mobile
                                            ->maxLength(255),
                                    ]),

                                // Pemilik proyek
                                Forms\Components\Select::make('owner_id')
                                    ->label(__('Project owner'))
                                    ->searchable()
                                    ->options(fn() => User::all()->pluck('name', 'id')->toArray())
                                    ->default(fn() => auth()->user()->id)
                                    ->required()
                                    ->columnSpan(['sm' => 12, 'lg' => 4]), // Responsif

                                // Status proyek
                                Forms\Components\Select::make('status_id')
                                    ->label(__('Project status'))
                                    ->searchable()
                                    ->options(fn() => ProjectStatus::all()->pluck('name', 'id')->toArray())
                                    ->default(fn() => ProjectStatus::where('is_default', true)->first()?->id)
                                    ->required()
                                    ->columnSpan(['sm' => 12, 'lg' => 4]), // Responsif

                                // Deskripsi proyek
                                Forms\Components\Textarea::make('description')
                                    ->label(__('Project description'))
                                    ->required()
                                    ->columnSpan(['sm' => 12, 'lg' => 8]), // Responsif

                                // Deadline
                                Forms\Components\DateTimePicker::make('deadline')
                                    ->label(__('Deadline'))
                                    ->required()
                                    ->reactive()
                                    ->minDate(now()->format('Y-m-d')) // Tanggal minimum adalah hari ini
                                    ->default(now()->setTime(0, 0))
                                    ->rules(['after_or_equal:now'])
                                    ->columnSpan(['sm' => 12, 'lg' => 3]), // Responsif
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
                Tables\Actions\Action::make('markAsComplete')
                   
                    ->label(fn($record) => $record->status_id === ProjectStatus::where('name', 'Completed')->first()?->id 
                        ? __('Cancel Complete') 
                        : __('Mark as Complete')) // Label berubah dinamis

                    ->icon(fn($record) => $record->status_id === ProjectStatus::where('name', 'Completed')->first()?->id 
                        ? 'heroicon-o-x-circle' 
                        : 'heroicon-o-check-circle') // Ikon berubah dinamis
                    ->color(fn($record) => $record->status_id === ProjectStatus::where('name', 'Completed')->first()?->id 
                        ? 'danger' 
                        : 'success') // Warna berubah dinamis
                    ->action(function ($record) {
                        $completedStatus = ProjectStatus::where('name', 'Completed')->first()?->id;
                        $inProgressStatus = ProjectStatus::where('name', 'On Progress')->first()?->id;
            
                        if ($record->status_id === $completedStatus) {
                            // Jika status "Completed", ubah menjadi "On Progress"
                            $record->status_id = $inProgressStatus;
                            Filament::notify('warning', __('The project completion has been canceled'));
                        } else {
                            // Jika status bukan "Completed", ubah menjadi "Completed"
                            $record->status_id = $completedStatus;
                            Filament::notify('success', __('The project has been marked as completed'));
                        }
            
                        $record->save();
                    })
                    ->requiresConfirmation(fn($record) => $record->status_id === ProjectStatus::where('name', 'Completed')->first()?->id 
                        ? __('Are you sure you want to revert this project to In Progress?') 
                        : __('Are you sure you want to mark this project as completed?')) // Konfirmasi dinamis
                    ->visible(fn() => Filament::auth()->user()->can('Mark as completed')), // Cek permission

                    Tables\Actions\Action::make('markAsApproved')
                    ->label(fn($record) => $record->status_id === ProjectStatus::where('name', 'Approved')->first()?->id
                        ? __('Cancel Approval')
                        : __('Mark as Approved')) // Label berubah dinamis
                    ->icon(fn($record) => $record->status_id === ProjectStatus::where('name', 'Approved')->first()?->id
                        ? 'heroicon-o-x-circle'
                        : 'heroicon-o-check-circle') // Ikon berubah dinamis
                    ->color(fn($record) => $record->status_id === ProjectStatus::where('name', 'Approved')->first()?->id
                        ? 'danger'
                        : 'primary') // Warna berubah dinamis
                    ->action(function ($record) {
                        $approvedStatus = ProjectStatus::where('name', 'Approved')->first()?->id;
                        $inProgressStatus = ProjectStatus::where('name', 'On Progress')->first()?->id;
                
                        if ($record->status_id === $approvedStatus) {
                            // Jika status "Approved", ubah menjadi "On Progress"
                            $record->status_id = $inProgressStatus;
                            Filament::notify('warning', __('The project approval has been canceled'));
                        } else {
                            // Jika status bukan "Approved", ubah menjadi "Approved"
                            $record->status_id = $approvedStatus;
                            Filament::notify('success', __('The project has been marked as approved'));
                        }
                
                        $record->save();
                    })
                    ->requiresConfirmation(fn($record) => $record->status_id === ProjectStatus::where('name', 'Approved')->first()?->id
                        ? __('Are you sure you want to revert this project to In Progress?')
                        : __('Are you sure you want to mark this project as approved?')) // Konfirmasi dinamis
                    ->visible(fn() => Filament::auth()->user()->can('Mark as approved')), // Cek permission                

                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\Action::make('exportLogHours')
                    //     ->label(__('Export hours'))
                    //     ->icon('heroicon-o-document-download')
                    //     ->color('secondary')
                    //     ->action(fn($record) => Excel::download(
                    //         new ProjectHoursExport($record),
                    //         'time_' . Str::slug($record->name) . '.csv',
                    //         \Maatwebsite\Excel\Excel::CSV,
                    //         ['Content-Type' => 'text/csv']
                    //     )),

                    Tables\Actions\Action::make('kanban')
                        ->label(
                            fn($record)
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
                // Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewProject::route('/{record}', function ($record) {}),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
