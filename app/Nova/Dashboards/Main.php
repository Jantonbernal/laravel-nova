<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewProjects;
use App\Nova\Metrics\NewTasks;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            NewProjects::make(),
            NewTasks::make(),
        ];
    }
}
