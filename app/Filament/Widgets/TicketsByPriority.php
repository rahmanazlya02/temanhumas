<?php

namespace App\Filament\Widgets;

use App\Models\TicketPriority;
use Filament\Widgets\DoughnutChartWidget;

class TicketsByPriority extends DoughnutChartWidget
{
    protected static ?int $sort = 3;
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
        return __('Tasks by Priority');
    }

    protected function getData(): array
    {
        $data = TicketPriority::withCount('tickets')->get();
        return [
            'datasets' => [
                [
                    'label' => __('Tickets by priorities'),
                    'data' => $data->pluck('tickets_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(0, 156, 0, 0.9)',
                        'rgba(210, 182, 0, 0.9)',
                        'rgba(219, 0, 15, 0.9)',
                    ],
                    'borderColor' => [
                        'rgb(5, 138, 5)',
                        'rgb(177, 154, 5)',
                        'rgb(190, 5, 17)',
                    ],
                    'hoverOffset' => 4
                ]
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
}
