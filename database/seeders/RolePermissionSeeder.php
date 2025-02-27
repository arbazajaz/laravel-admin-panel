<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Cache Before Seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'manage_reports',
            'assign_reports',
            'view_assigned_reports',
            'create_own_report',
            'edit_own_report',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Assign Permissions
        $superAdmin->givePermissionTo(Permission::all()); // Full access
        $supervisor->givePermissionTo(['view_assigned_reports', 'manage_reports']);
        $user->givePermissionTo(['create_own_report', 'edit_own_report']);

        // Create Test Users (Optional)
        $this->createTestUsers();
    }

    private function createTestUsers()
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@example.com',
                'password' => bcrypt('password'),
                'role' => 'supervisor',
            ],
            [
                'name' => 'user',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}
