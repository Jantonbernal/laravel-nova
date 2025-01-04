<?php

namespace App\Nova\Metrics;

use App\Models\Project;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Nova;

class NewProjects extends Value
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ValueResult
    {
        // return $this->count($request, Project::class);

        // Filtrar las tareas asignadas al usuario autenticado
        $user = $request->user();

        if (! $user) {
            // Si no hay usuario autenticado, devolver un valor por defecto
            return $this->result(0);
        }

        // Filtrar las tareas asignadas al usuario autenticado
        $tasksQuery = Project::where('user_id', $user->id);

        return $this->count($request, $tasksQuery);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array<int|string, string>
     */
    public function ranges(): array
    {
        return [
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            365 => Nova::__('365 Days'),
            'TODAY' => Nova::__('Today'),
            'MTD' => Nova::__('Month To Date'),
            'QTD' => Nova::__('Quarter To Date'),
            'YTD' => Nova::__('Year To Date'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): ?DateTimeInterface
    {
        // return now()->addMinutes(5);

        return null;
    }
}
