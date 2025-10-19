<?php

namespace Database\Seeders;

use App\Models\Role;
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
            ['name' => 'user', 'description' => 'Regular user with read-only access'],
            ['name' => 'manager', 'description' => 'Manager with CRUD access except delete'],
            ['name' => 'admin', 'description' => 'Administrator with full CRUD access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
