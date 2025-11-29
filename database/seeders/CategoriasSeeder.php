<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Alimentos para Perros',
                'descripcion' => 'Alimentos secos, húmedos y snacks para perros de todas las edades',
                'activo' => true,
            ],
            [
                'nombre' => 'Alimentos para Gatos',
                'descripcion' => 'Alimentos premium, húmedos y snacks para gatos',
                'activo' => true,
            ],
            [
                'nombre' => 'Juguetes',
                'descripcion' => 'Juguetes interactivos, peluches y accesorios de entretenimiento',
                'activo' => true,
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Collares, correas, arneses y ropa para mascotas',
                'activo' => true,
            ],
            [
                'nombre' => 'Higiene y Cuidado',
                'descripcion' => 'Champús, cepillos, cortauñas y productos de higiene',
                'activo' => true,
            ],
            [
                'nombre' => 'Camas y Casas',
                'descripcion' => 'Camas, colchonetas, casas y transportadores',
                'activo' => true,
            ],
            [
                'nombre' => 'Comederos y Bebederos',
                'descripcion' => 'Comederos automáticos, bebederos y fuentes de agua',
                'activo' => true,
            ],
            [
                'nombre' => 'Medicamentos y Suplementos',
                'descripcion' => 'Vitaminas, suplementos y productos veterinarios',
                'activo' => true,
            ],
            [
                'nombre' => 'Peceras y Acuarios',
                'descripcion' => 'Acuarios, filtros, bombas y decoración',
                'activo' => true,
            ],
            [
                'nombre' => 'Jaulas y Hábitats',
                'descripcion' => 'Jaulas para aves, hamsters, conejos y roedores',
                'activo' => false, // Una inactiva para testing
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
