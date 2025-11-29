<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mascotasfelices.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'activo' => true,
        ]);

        // Vendedor 1
        User::create([
            'name' => 'María González',
            'email' => 'maria@mascotasfelices.com',
            'password' => Hash::make('vendedor123'),
            'role' => 'vendedor',
            'activo' => true,
        ]);

        // Vendedor 2
        User::create([
            'name' => 'Carlos Ramírez',
            'email' => 'carlos@mascotasfelices.com',
            'password' => Hash::make('vendedor123'),
            'role' => 'vendedor',
            'activo' => true,
        ]);

        // Inventario
        User::create([
            'name' => 'Ana López',
            'email' => 'ana@mascotasfelices.com',
            'password' => Hash::make('inventario123'),
            'role' => 'inventario',
            'activo' => true,
        ]);

        // Usuario inactivo
        User::create([
            'name' => 'Pedro Martínez',
            'email' => 'pedro@mascotasfelices.com',
            'password' => Hash::make('password123'),
            'role' => 'vendedor',
            'activo' => false,
        ]);
    }
}
