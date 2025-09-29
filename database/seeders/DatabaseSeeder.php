<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden
        $this->call([
            RoleSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
        ]);

        // Crear usuarios de prueba con roles
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $userRole = \App\Models\Role::where('name', 'user')->first();

        // Usuario administrador
        $admin = User::firstOrCreate([
            'email' => 'admin@minierp.com'
        ], [
            'name' => 'Administrador ERP',
            'email' => 'admin@minierp.com',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Usuario regular
        $user = User::firstOrCreate([
            'email' => 'user@minierp.com'
        ], [
            'name' => 'Usuario ERP',
            'email' => 'user@minierp.com',
            'password' => bcrypt('password'),
        ]);
        $user->roles()->syncWithoutDetaching([$userRole->id]);

        // Usuario de prueba existente (actualizar con rol)
        $testUser = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $testUser->roles()->syncWithoutDetaching([$userRole->id]);
    }
}
