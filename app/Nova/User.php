<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
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
            Email::make('Cliente', 'email')->rules('required', 'max:50', 'email:rfc,dns')->sortable(),
            Password::make('Contraseña', 'Password')->rules('required', 'max:255'),
            // Text::make('email')->sortable(),
            MorphToMany::make('Roles', 'roles', \Sereny\NovaPermissions\Nova\Role::class),
            MorphToMany::make('Permissions', 'permissions', \Sereny\NovaPermissions\Nova\Permission::class),
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

        return $user && $request->user()->can('viewTask', $this->resource);
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

        return $user && $request->user()->can('updateTask');
    }

    /**
     * Determine if the user can delete the given model.
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        $user = $request->user();

        return $user && $request->user()->can('deleteTask');
    }
}
