<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketComment;
use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class LatestComments extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public function mount(): void
    {
        self::$heading = __('Latest Task Comments');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        $query = TicketComment::query()
        ->whereNull('deleted_at')
        ->whereHas('ticket.project', function ($query) {
            $query->whereNull('deleted_at');});

        if ($user->hasRole('Ketua Tim Humas')) {
            return $query->latest()->limit(5); // Semua komentar
        } elseif ($user->hasRole('Koordinator Subtim')) {
            return $query
                ->whereHas('ticket', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->where('owner_id', $user->id)
                            ->orWhereHas('project', function ($query) use ($user) {
                                $query->where('owner_id', $user->id)
                                    ->orWhereHas('users', function ($query) use ($user) {
                                        $query->where('users.id', $user->id);
                                    });
                            });
                    });
                })
                ->latest()
                ->limit(5);
        } else { // Anggota
            return $query
                ->whereHas('ticket', function ($query) use ($user) {
                    $query->where('responsible_id', $user->id);
                })
                ->latest()
                ->limit(5);
        }
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('ticket')
                ->label(__('Task'))
                ->formatStateUsing(function ($state) {
                    return new HtmlString('
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-400 font-medium text-xs">
                            ' . $state->project->name . '
                        </span>
                        <span>
                            <span class="text-sm text-gray-400">|</span> '
                        . $state->name . '
                        </span>
                    </div>
                ');
                }),

            Tables\Columns\TextColumn::make('user.name')
                ->label(__('Owner'))
                ->formatStateUsing(fn($record) => view('components.user-avatar', ['user' => $record->user])),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Commented at'))
                ->dateTime()
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label(__('View'))
                ->icon('heroicon-s-eye')
                ->color('secondary')
                ->modalHeading(__('Comment details'))
                ->modalButton(__('View ticket'))
                ->form([
                    Textarea::make('content')
                        ->label(__('Content'))
                        ->default(fn($record) => $record->content)
                        ->disabled()
                ])
                ->action(function ($record) {
                    return redirect()->route('filament.resources.tickets.view',['record' => $record->ticket->id]);
                }),
        ];
    }
}
