<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FavoriteProjects;
use App\Filament\Widgets\LatestActivities;
use App\Filament\Widgets\LatestComments;
use App\Filament\Widgets\LatestProjects;
use App\Filament\Widgets\TicketsByType;
use App\Filament\Widgets\TasksByStatus;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static bool $shouldRegisterNavigation = false;

    protected function getColumns(): int | array
    {
        return 6;
    }

    protected function getWidgets(): array
    {
        return [
            TasksByStatus::class,
            FavoriteProjects::class,
            LatestActivities::class,
            LatestProjects::class,
            TicketsByType::class,
            LatestComments::class,
        ];
    }
}
