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
        // Filtrar las tareas asignadas al usuario autenticado
        $user = $request->user();

        if (! $user) {
            // Si no hay usuario autenticado, devolver un valor por defecto
            return $this->result(0);
        }

        // Obtener el rol del usuario
        $role = $user->roles()->first();

        if (! $role) {
            // Si el usuario no tiene un rol asignado, devolver un valor por defecto
            return $this->result(0);
        }

        // Filtrar proyectos en base al rol
        $projectsQuery = Project::query();

        if ($role->name === 'super-admin') {
            // super-admin ve todos los proyectos
            return $this->count($request, $projectsQuery);
        } else {
            // Filtrar los proyectos asignadas al usuario autenticado
            $projectsQuery = Project::where('user_id', $user->id);
        }

        return $this->count($request, $projectsQuery);
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
