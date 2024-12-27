<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class LatestProjects extends BaseWidget
{
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public function mount(): void
    {
        self::$heading = __('Latest Project Activities');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List projects');
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        $query = Project::query()->whereNull('deleted_at'); 

        if ($user->hasRole('Ketua Tim Humas')) {
            return $query->latest()->limit(5);
        }

        if ($user->hasRole('Koordinator Subtim')) {
            return $query
                ->where(function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        });
                })
                ->latest()
                ->limit(5);
        }

        return $query
            ->whereHas('tickets', function ($query) use ($user) {
                $query->where('responsible_id', $user->id);
            })
            ->latest()
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('Project'))
                ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="w-full flex items-center gap-2">
                                <div style=\'background-image: url("' . $record->cover . '")\'
                                 class="w-8 h-8 bg-cover bg-center bg-no-repeat"></div>
                                ' . $record->name . '
                            </div>
                        ')),

            Tables\Columns\TextColumn::make('owner.name')
                ->label(__('Project owner')),

            Tables\Columns\TextColumn::make('status.name')
                ->label(__('Project status'))
                ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="flex items-center gap-2">
                                <span class="filament-tables-color-column relative flex h-6 w-6 rounded-md"
                                    style="background-color: ' . $record->status->color . '"></span>
                                <span>' . $record->status->name . '</span>
                            </div>
                        ')),
        ];
    }
}
