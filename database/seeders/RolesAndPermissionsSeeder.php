<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    // Reset cached permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Permission types
    $permissions = [
      'create',
      'read',
      'update',
      'delete',
    ];

    // Role names
    $roles = [
      'admin',
      'user',
      'patient',
    ];

    // Entities requiring CRUD permissions
    $entities = [
      'users',
      'roles',
      'laboratory',
      'pharmacy',
      'radiology',
      'billing',
      'report',
      'admission',
      'appointments',
      'patients',
      'opticals',
      'settings',
    ];

    // Create CRUD permissions
    foreach ($permissions as $permission) {
      foreach ($entities as $entity) {
        Permission::firstOrCreate([
          'name' => "$permission-$entity",
        ]);
      }
    }

    // Create special single permission: check-in
    Permission::firstOrCreate(['name' => 'check-in']);

    // Create roles
    foreach ($roles as $roleName) {
      Role::firstOrCreate(['name' => $roleName]);
    }

    // Assign all permissions to admin role
    $admin = Role::where('name', 'admin')->first();
    $admin->syncPermissions(Permission::all());

    // Assign admin role to user ID 1 if exists
    $user = User::find(1);
    if ($user) {
      $user->assignRole('admin');
    }
  }
}
