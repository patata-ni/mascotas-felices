<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando productos de prueba...');

        $categorias = Categoria::all();
        
        if ($categorias->isEmpty()) {
            $this->command->error('No hay categorías. Ejecuta primero los seeders de categorías.');
            return;
        }

        $productos = [
            // Alimentos
            [
                'nombre' => 'Royal Canin Adult',
                'descripcion' => 'Alimento premium para perros adultos 15kg',
                'codigo_barras' => '7501234567891',
                'categoria' => 'Alimentos',
                'precio_compra' => 180.00,
                'precio_venta' => 250.00,
                'stock_actual' => 45,
                'stock_minimo' => 10,
            ],
            [
                'nombre' => 'Pro Plan Cachorro',
                'descripcion' => 'Alimento para cachorros todas las razas 7.5kg',
                'codigo_barras' => '7501234567892',
                'categoria' => 'Alimentos',
                'precio_compra' => 120.00,
                'precio_venta' => 165.00,
                'stock_actual' => 38,
                'stock_minimo' => 15,
            ],
            [
                'nombre' => 'Whiskas Adulto',
                'descripcion' => 'Alimento para gatos adultos sabor pescado 1kg',
                'codigo_barras' => '7501234567893',
                'categoria' => 'Alimentos',
                'precio_compra' => 15.00,
                'precio_venta' => 22.00,
                'stock_actual' => 120,
                'stock_minimo' => 30,
            ],
            [
                'nombre' => 'Dog Chow Adulto',
                'descripcion' => 'Alimento para perros adultos razas medianas 21kg',
                'codigo_barras' => '7501234567894',
                'categoria' => 'Alimentos',
                'precio_compra' => 150.00,
                'precio_venta' => 210.00,
                'stock_actual' => 28,
                'stock_minimo' => 8,
            ],
            [
                'nombre' => 'Cat Chow Gatitos',
                'descripcion' => 'Alimento para gatitos en crecimiento 3kg',
                'codigo_barras' => '7501234567895',
                'categoria' => 'Alimentos',
                'precio_compra' => 35.00,
                'precio_venta' => 52.00,
                'stock_actual' => 55,
                'stock_minimo' => 20,
            ],
            
            // Accesorios
            [
                'nombre' => 'Collar de Cuero',
                'descripcion' => 'Collar ajustable de cuero genuino para perros medianos',
                'codigo_barras' => '7501234567896',
                'categoria' => 'Accesorios',
                'precio_compra' => 25.00,
                'precio_venta' => 42.00,
                'stock_actual' => 65,
                'stock_minimo' => 15,
            ],
            [
                'nombre' => 'Correa Retráctil',
                'descripcion' => 'Correa extensible hasta 5 metros para perros hasta 20kg',
                'codigo_barras' => '7501234567897',
                'categoria' => 'Accesorios',
                'precio_compra' => 40.00,
                'precio_venta' => 68.00,
                'stock_actual' => 32,
                'stock_minimo' => 10,
            ],
            [
                'nombre' => 'Plato Doble Acero',
                'descripcion' => 'Juego de 2 platos de acero inoxidable con base antideslizante',
                'codigo_barras' => '7501234567898',
                'categoria' => 'Accesorios',
                'precio_compra' => 30.00,
                'precio_venta' => 50.00,
                'stock_actual' => 48,
                'stock_minimo' => 12,
            ],
            [
                'nombre' => 'Cama Acolchada',
                'descripcion' => 'Cama acolchada tamaño mediano para perros y gatos',
                'codigo_barras' => '7501234567899',
                'categoria' => 'Accesorios',
                'precio_compra' => 60.00,
                'precio_venta' => 95.00,
                'stock_actual' => 22,
                'stock_minimo' => 8,
            ],
            [
                'nombre' => 'Casa Plástica Grande',
                'descripcion' => 'Casa de plástico resistente para perros grandes',
                'codigo_barras' => '7501234567800',
                'categoria' => 'Accesorios',
                'precio_compra' => 180.00,
                'precio_venta' => 280.00,
                'stock_actual' => 8,
                'stock_minimo' => 3,
            ],
            
            // Juguetes
            [
                'nombre' => 'Pelota Kong Clásica',
                'descripcion' => 'Pelota de caucho natural resistente, tamaño mediano',
                'codigo_barras' => '7501234567801',
                'categoria' => 'Juguetes',
                'precio_compra' => 22.00,
                'precio_venta' => 38.00,
                'stock_actual' => 75,
                'stock_minimo' => 20,
            ],
            [
                'nombre' => 'Cuerda Dental',
                'descripcion' => 'Juguete de cuerda trenzada para limpieza dental',
                'codigo_barras' => '7501234567802',
                'categoria' => 'Juguetes',
                'precio_compra' => 12.00,
                'precio_venta' => 22.00,
                'stock_actual' => 95,
                'stock_minimo' => 25,
            ],
            [
                'nombre' => 'Ratón con Catnip',
                'descripcion' => 'Juguete ratón de peluche con hierba gatera para gatos',
                'codigo_barras' => '7501234567803',
                'categoria' => 'Juguetes',
                'precio_compra' => 8.00,
                'precio_venta' => 15.00,
                'stock_actual' => 140,
                'stock_minimo' => 40,
            ],
            [
                'nombre' => 'Frisbee para Perros',
                'descripcion' => 'Disco volador de goma suave para juegos al aire libre',
                'codigo_barras' => '7501234567804',
                'categoria' => 'Juguetes',
                'precio_compra' => 18.00,
                'precio_venta' => 32.00,
                'stock_actual' => 42,
                'stock_minimo' => 15,
            ],
            
            // Higiene
            [
                'nombre' => 'Shampoo Antipulgas',
                'descripcion' => 'Shampoo medicado antipulgas y garrapatas 500ml',
                'codigo_barras' => '7501234567805',
                'categoria' => 'Higiene y Salud',
                'precio_compra' => 25.00,
                'precio_venta' => 42.00,
                'stock_actual' => 68,
                'stock_minimo' => 20,
            ],
            [
                'nombre' => 'Toallitas Húmedas',
                'descripcion' => 'Toallitas de limpieza para mascotas, paquete de 80 unidades',
                'codigo_barras' => '7501234567806',
                'categoria' => 'Higiene y Salud',
                'precio_compra' => 12.00,
                'precio_venta' => 20.00,
                'stock_actual' => 88,
                'stock_minimo' => 25,
            ],
            [
                'nombre' => 'Cepillo Dental',
                'descripcion' => 'Cepillo dental con pasta incluida para perros y gatos',
                'codigo_barras' => '7501234567807',
                'categoria' => 'Higiene y Salud',
                'precio_compra' => 18.00,
                'precio_venta' => 30.00,
                'stock_actual' => 52,
                'stock_minimo' => 15,
            ],
            [
                'nombre' => 'Arena Sanitaria',
                'descripcion' => 'Arena aglomerante para gatos con control de olores 10kg',
                'codigo_barras' => '7501234567808',
                'categoria' => 'Higiene y Salud',
                'precio_compra' => 28.00,
                'precio_venta' => 45.00,
                'stock_actual' => 35,
                'stock_minimo' => 10,
            ],
            [
                'nombre' => 'Cortaúñas Profesional',
                'descripcion' => 'Cortaúñas de acero inoxidable con protección de seguridad',
                'codigo_barras' => '7501234567809',
                'categoria' => 'Higiene y Salud',
                'precio_compra' => 22.00,
                'precio_venta' => 38.00,
                'stock_actual' => 44,
                'stock_minimo' => 12,
            ],
            
            // Medicamentos
            [
                'nombre' => 'Desparasitante Interno',
                'descripcion' => 'Antiparasitario interno para perros y gatos, tabletas',
                'codigo_barras' => '7501234567810',
                'categoria' => 'Medicamentos',
                'precio_compra' => 35.00,
                'precio_venta' => 58.00,
                'stock_actual' => 85,
                'stock_minimo' => 30,
            ],
            [
                'nombre' => 'Vitaminas Multi-pet',
                'descripcion' => 'Complejo vitamínico para mascotas, frasco de 60 tabletas',
                'codigo_barras' => '7501234567811',
                'categoria' => 'Medicamentos',
                'precio_compra' => 45.00,
                'precio_venta' => 72.00,
                'stock_actual' => 62,
                'stock_minimo' => 20,
            ],
            [
                'nombre' => 'Antipulgas Pipeta',
                'descripcion' => 'Pipeta antipulgas de aplicación tópica, pack de 3 unidades',
                'codigo_barras' => '7501234567812',
                'categoria' => 'Medicamentos',
                'precio_compra' => 55.00,
                'precio_venta' => 88.00,
                'stock_actual' => 48,
                'stock_minimo' => 15,
            ],
            [
                'nombre' => 'Suplemento Articular',
                'descripcion' => 'Suplemento para cuidado de articulaciones, 30 cápsulas',
                'codigo_barras' => '7501234567813',
                'categoria' => 'Medicamentos',
                'precio_compra' => 68.00,
                'precio_venta' => 105.00,
                'stock_actual' => 28,
                'stock_minimo' => 10,
            ],
            
            // Snacks
            [
                'nombre' => 'Galletas Dentales',
                'descripcion' => 'Galletas para limpieza dental perros medianos, 500g',
                'codigo_barras' => '7501234567814',
                'categoria' => 'Snacks y Premios',
                'precio_compra' => 18.00,
                'precio_venta' => 30.00,
                'stock_actual' => 92,
                'stock_minimo' => 25,
            ],
            [
                'nombre' => 'Hueso de Cuero',
                'descripcion' => 'Hueso masticable de cuero natural para perros',
                'codigo_barras' => '7501234567815',
                'categoria' => 'Snacks y Premios',
                'precio_compra' => 15.00,
                'precio_venta' => 25.00,
                'stock_actual' => 115,
                'stock_minimo' => 30,
            ],
            [
                'nombre' => 'Snack de Pollo',
                'descripcion' => 'Tiras de pollo deshidratado, bolsa de 200g',
                'codigo_barras' => '7501234567816',
                'categoria' => 'Snacks y Premios',
                'precio_compra' => 22.00,
                'precio_venta' => 38.00,
                'stock_actual' => 78,
                'stock_minimo' => 20,
            ],
            [
                'nombre' => 'Premios para Gatos',
                'descripcion' => 'Premios crujientes sabor salmón para gatos, 60g',
                'codigo_barras' => '7501234567817',
                'categoria' => 'Snacks y Premios',
                'precio_compra' => 8.00,
                'precio_venta' => 14.00,
                'stock_actual' => 135,
                'stock_minimo' => 35,
            ],
        ];

        foreach ($productos as $productoData) {
            $categoria = $categorias->where('nombre', $productoData['categoria'])->first();
            
            if ($categoria) {
                Producto::create([
                    'nombre' => $productoData['nombre'],
                    'descripcion' => $productoData['descripcion'],
                    'codigo_barras' => $productoData['codigo_barras'],
                    'categoria_id' => $categoria->id,
                    'precio_compra' => $productoData['precio_compra'],
                    'precio_venta' => $productoData['precio_venta'],
                    'stock_actual' => $productoData['stock_actual'],
                    'stock_minimo' => $productoData['stock_minimo'],
                    'activo' => true,
                ]);

                $this->command->info("Producto creado: {$productoData['nombre']}");
            }
        }

        $this->command->info('✅ Productos de prueba creados exitosamente!');
        $this->command->info('Total productos: ' . Producto::count());
    }
}
