<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'Project',
            'Task',
            'User',
            'Role',
            'Permission',
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::create(['group' => $item, 'name' => 'viewAny'.$item]);
            Permission::create(['group' => $item, 'name' => 'view'.$item]);
            Permission::create(['group' => $item, 'name' => 'update'.$item]);
            Permission::create(['group' => $item, 'name' => 'create'.$item]);
            Permission::create(['group' => $item, 'name' => 'delete'.$item]);
            Permission::create(['group' => $item, 'name' => 'destroy'.$item]);
        });

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        $user = User::where('email', 'juanma.03.17.07@gmail.com')->first(); // Change this to email.
        if (! $user) {
            $user = User::firstOrCreate(
                ['email' => 'juanma.03.17.07@gmail.com'],
                ['name' => 'Juanma', 'password' => bcrypt('password')]
            );
            $user->assignRole('super-admin');
        } else {
            echo "El usuario no se encontró.\n";
        }

        $role = Role::create(['name' => 'lector']);
        $role->givePermissionTo([8]);

    }
}
