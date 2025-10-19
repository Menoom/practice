<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Regular user with limited permissions',
                'permissions' => ['view_tasks', 'update_task_status']
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Manager with CRUD permissions except delete',
                'permissions' => ['view_tasks', 'create_tasks', 'update_tasks', 'assign_tasks', 'view_users', 'update_users']
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system administrator with all permissions',
                'permissions' => ['*'] // All permissions
            ]
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
