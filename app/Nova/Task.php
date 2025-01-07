<?php

namespace App\Nova;

use App\Nova\Policies\TaskPolicy;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Task extends Resource
{
    /**
     * The policy the resource corresponds to.
     *
     * @var class-string
     */
    public static $policy = TaskPolicy::class;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Task>
     */
    public static $model = \App\Models\Task::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Nombre', 'name')->sortable()->rules('required', 'max:255'),
            BelongsTo::make('Proyecto', 'Project', Project::class),
            BelongsTo::make('Usuario', 'User', User::class),

            Images::make('Images', 'images') // 'images' debe coincidir con la colección registrada en el modelo
                ->conversionOnIndexView('thumb') // Conversión usada en la vista del índice
                ->conversionOnDetailView('thumb') // Conversión usada en la vista de detalle
                ->conversionOnForm('thumb') // Conversión usada en los formularios
                ->rules('required'), // Reglas de validación
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the menu that should represent the resource.
     *
     * @return \Laravel\Nova\Menu\MenuItem
     */
    public function menu(Request $request)
    {
        $user = $request->user();

        // Si no hay usuario autenticado, devolver el menú sin un contador
        if (! $user) {
            return parent::menu($request);
        }

        // Obtener el rol del usuario
        $role = $user->roles()->first();

        return parent::menu($request)->withBadge(function () use ($user, $role) {
            // Si no hay rol, no mostrar el contador
            if (! $role) {
                return 0;
            }

            // Filtrar proyectos según el rol
            $projectsQuery = static::$model::query();

            if ($role->name === 'super-admin') {
                // super-admin ve todos los proyectos
                return $projectsQuery->count();
            }

            // Filtrar proyectos asignados al usuario autenticado
            return $projectsQuery->where('user_id', $user->id)->count();
        });
    }

    /**
     * Return a replicated resource.
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function replicate()
    {
        return tap(parent::replicate(), function ($resource) {
            $model = $resource->model();

            $model->name = 'Duplicate of '.$model->name;
        });
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if (! $user) {
            return $query->whereRaw('1 = 0'); // No devuelve resultados si no hay usuario.
        }

        return $query->where('user_id', $user->id);
    }
}
