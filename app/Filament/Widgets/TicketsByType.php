<?php

namespace App\Filament\Widgets;

use App\Models\TicketType;
use Filament\Widgets\DoughnutChartWidget;

class TicketsByType extends DoughnutChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Chart';
    protected static ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    protected function getHeading(): string
    {
        return __('Tasks by Types');
    }

    protected function getData(): array
    {
        $user = auth()->user(); 
        $query = TicketType::query()->withCount(['tickets' => function ($query) use ($user) {
            $query->whereNull('deleted_at'); 
            if ($user->hasRole('Ketua Tim Humas')) {
                return; 
            } elseif ($user->hasRole('Koordinator Subtim')) {
                $query->where(function ($query) use ($user) {
                    $query->where('responsible_id', $user->id)
                        ->orWhere('owner_id', $user->id)
                        ->orWhereHas('project', function ($query) use ($user) {
                            $query->where('owner_id', $user->id)
                                ->orWhereHas('users', function ($query) use ($user) {
                                    $query->where('users.id', $user->id);
                                });
                        });
                });
            } elseif ($user->hasRole('Anggota')) {
                $query->where('responsible_id', $user->id);
            }
        }]);
    
        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => __('Tickets by types'),
                    'data' => $data->pluck('tickets_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(32, 157, 182, 0.9)',
                        'rgba(136, 84, 208, 0.9)',
                        'rgba(255, 178, 0, 0.9)	'
                    ],
                    'borderColor' => [
                        'rgba(32, 157, 182, 1)',
                        'rgba(136, 84, 208, 1)',
                        'rgba(255, 178, 0, 1)	'
                    ],
                    'hoverOffset' => 4
                ]
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
}
