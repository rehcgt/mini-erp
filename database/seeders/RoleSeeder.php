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
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Usuario con acceso completo al sistema',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user',
                'display_name' => 'Usuario',
                'description' => 'Usuario con acceso limitado al sistema',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
