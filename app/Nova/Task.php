<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Task extends Resource
{
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
        // return parent::menu($request)->withBadge(function () {
        //     return static::$model::count();
        // });

        $user = $request->user();

        // Si no hay usuario autenticado, devolver el menú sin un contador
        if (! $user) {
            return parent::menu($request);
        }

        return parent::menu($request)->withBadge(function () use ($user) {
            // Filtrar los registros relacionados con el usuario autenticado
            return static::$model::where('user_id', $user->id)->count();
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

    /**
     * Determine if the user can view any models.
     *
     * @param  string|null  $model
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        $user = $request->user();

        return $user && $request->user()->can('viewAnyTask');
    }

    public function authorizedToView(Request $request)
    {
        // Determina si el usuario puede ver este recurso en particular
        $user = $request->user();

        return $user && $request->user()->can('viewTask', $this->resource) && $this->user_id === $user->id;
    }

    /**
     * Determine if the user can create models.
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        $user = $request->user();

        return $user && $request->user()->can('createTask');
    }

    /**
     * Determine if the user can update the given model.
     *
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        $user = $request->user();

        return $user && $request->user()->can('updateTask') && $this->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the given model.
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        $user = $request->user();

        return $user && $request->user()->can('deleteTask') && $this->user_id === $user->id;
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
