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
        $data = TicketType::withCount('tickets')->get();
        return [
            'datasets' => [
                [
                    'label' => __('Tickets by types'),
                    'data' => $data->pluck('tickets_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(5, 138, 145, 0.9)',
                        'rgba(182, 201, 9, 0.9)',
                        'rgba(223, 64, 1, 0.9)	'
                    ],
                    'borderColor' => [
                        'rgb(7, 160, 168)',
                        'rgb(204, 225, 13)',
                        'rgb(199, 65, 12)	'
                    ],
                    'hoverOffset' => 4
                ]
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
}
