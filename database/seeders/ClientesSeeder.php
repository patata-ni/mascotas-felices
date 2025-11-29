<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Carbon\Carbon;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando clientes de prueba...');

        $clientes = [
            [
                'nombre' => 'María Rodríguez García',
                'documento' => '12345678',
                'tipo_documento' => 'DNI',
                'telefono' => '987654321',
                'email' => 'maria.rodriguez@example.com',
                'direccion' => 'Av. Arequipa 1234, Miraflores, Lima',
                'fecha_nacimiento' => '1990-05-15',
                'puntos_fidelidad' => 150,
                'activo' => true,
            ],
            [
                'nombre' => 'Carlos Mendoza Silva',
                'documento' => '23456789',
                'tipo_documento' => 'DNI',
                'telefono' => '976543210',
                'email' => 'carlos.mendoza@example.com',
                'direccion' => 'Jr. Junín 567, Cercado de Lima',
                'fecha_nacimiento' => '1985-08-22',
                'puntos_fidelidad' => 320,
                'activo' => true,
            ],
            [
                'nombre' => 'Ana Torres Vega',
                'documento' => '34567890',
                'tipo_documento' => 'DNI',
                'telefono' => '965432109',
                'email' => 'ana.torres@example.com',
                'direccion' => 'Av. Javier Prado 890, San Isidro, Lima',
                'fecha_nacimiento' => '1992-03-10',
                'puntos_fidelidad' => 580,
                'activo' => true,
            ],
            [
                'nombre' => 'Luis Fernández Pérez',
                'documento' => '45678901',
                'tipo_documento' => 'DNI',
                'telefono' => '954321098',
                'email' => 'luis.fernandez@example.com',
                'direccion' => 'Calle Los Olivos 234, Surco, Lima',
                'fecha_nacimiento' => '1988-11-30',
                'puntos_fidelidad' => 95,
                'activo' => true,
            ],
            [
                'nombre' => 'Rosa Quispe Mamani',
                'documento' => '56789012',
                'tipo_documento' => 'DNI',
                'telefono' => '943210987',
                'email' => 'rosa.quispe@example.com',
                'direccion' => 'Av. Venezuela 456, Breña, Lima',
                'fecha_nacimiento' => '1995-07-18',
                'puntos_fidelidad' => 220,
                'activo' => true,
            ],
            [
                'nombre' => 'Pedro Sánchez Ruiz',
                'documento' => '67890123',
                'tipo_documento' => 'DNI',
                'telefono' => '932109876',
                'email' => 'pedro.sanchez@example.com',
                'direccion' => 'Jr. Lampa 789, Rímac, Lima',
                'fecha_nacimiento' => '1983-12-05',
                'puntos_fidelidad' => 450,
                'activo' => true,
            ],
            [
                'nombre' => 'Elena Castro Díaz',
                'documento' => '78901234',
                'tipo_documento' => 'DNI',
                'telefono' => '921098765',
                'email' => 'elena.castro@example.com',
                'direccion' => 'Av. Universitaria 123, Los Olivos, Lima',
                'fecha_nacimiento' => '1997-04-25',
                'puntos_fidelidad' => 75,
                'activo' => true,
            ],
            [
                'nombre' => 'Jorge Vargas López',
                'documento' => '89012345',
                'tipo_documento' => 'DNI',
                'telefono' => '910987654',
                'email' => 'jorge.vargas@example.com',
                'direccion' => 'Calle Lima 345, San Miguel, Lima',
                'fecha_nacimiento' => '1980-09-14',
                'puntos_fidelidad' => 680,
                'activo' => true,
            ],
            [
                'nombre' => 'Carmen Huamán Flores',
                'documento' => '90123456',
                'tipo_documento' => 'DNI',
                'telefono' => '909876543',
                'email' => 'carmen.huaman@example.com',
                'direccion' => 'Av. Colonial 678, Callao',
                'fecha_nacimiento' => '1991-06-08',
                'puntos_fidelidad' => 125,
                'activo' => true,
            ],
            [
                'nombre' => 'Ricardo Paredes Ramos',
                'documento' => '01234567',
                'tipo_documento' => 'DNI',
                'telefono' => '998765432',
                'email' => 'ricardo.paredes@example.com',
                'direccion' => 'Jr. Cusco 901, La Victoria, Lima',
                'fecha_nacimiento' => '1986-02-20',
                'puntos_fidelidad' => 390,
                'activo' => true,
            ],
            [
                'nombre' => 'Sofía Morales Cruz',
                'documento' => '11234568',
                'tipo_documento' => 'DNI',
                'telefono' => '987654322',
                'email' => 'sofia.morales@example.com',
                'direccion' => 'Av. Benavides 234, Miraflores, Lima',
                'fecha_nacimiento' => '1993-10-12',
                'puntos_fidelidad' => 510,
                'activo' => true,
            ],
            [
                'nombre' => 'Miguel Velasco Gutiérrez',
                'documento' => '21234568',
                'tipo_documento' => 'DNI',
                'telefono' => '976543211',
                'email' => 'miguel.velasco@example.com',
                'direccion' => 'Calle San Martín 567, Pueblo Libre, Lima',
                'fecha_nacimiento' => '1998-01-28',
                'puntos_fidelidad' => 42,
                'activo' => true,
            ],
            [
                'nombre' => 'Patricia Rojas Salazar',
                'documento' => '31234568',
                'tipo_documento' => 'DNI',
                'telefono' => '965432100',
                'email' => 'patricia.rojas@example.com',
                'direccion' => 'Av. Arenales 890, Lince, Lima',
                'fecha_nacimiento' => '1989-05-16',
                'puntos_fidelidad' => 280,
                'activo' => true,
            ],
            [
                'nombre' => 'Daniel Campos Reyes',
                'documento' => '41234568',
                'tipo_documento' => 'DNI',
                'telefono' => '954321099',
                'email' => 'daniel.campos@example.com',
                'direccion' => 'Jr. Ancash 123, Cercado de Lima',
                'fecha_nacimiento' => '1996-08-03',
                'puntos_fidelidad' => 0,
                'activo' => true,
            ],
            [
                'nombre' => 'Lucía Herrera Ortiz',
                'documento' => '51234568',
                'tipo_documento' => 'DNI',
                'telefono' => '943210988',
                'email' => 'lucia.herrera@example.com',
                'direccion' => 'Av. Angamos 456, Surquillo, Lima',
                'fecha_nacimiento' => '1994-12-11',
                'puntos_fidelidad' => 165,
                'activo' => true,
            ],
        ];

        foreach ($clientes as $clienteData) {
            // Fecha de registro aleatoria en los últimos 12 meses
            $fechaRegistro = Carbon::now()->subDays(rand(0, 365));
            
            Cliente::create(array_merge($clienteData, [
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro,
            ]));

            $this->command->info("Cliente creado: {$clienteData['nombre']}");
        }

        $this->command->info('✅ Clientes de prueba creados exitosamente!');
        $this->command->info('Total clientes: ' . Cliente::count());
    }
}
