<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = Project::pluck('id');
        $projectSelected = fake()->randomElement($projects);

        $users = User::pluck('id');
        $userSelected = fake()->randomElement($users);

        return [
            'name' => fake()->name(),
            'project_id' => $projectSelected,
            'user_id' => $userSelected,
        ];
    }
}
