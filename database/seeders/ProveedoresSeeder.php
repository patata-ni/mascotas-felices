<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedoresSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Alimentos Pet Premium S.A.',
                'ruc' => '20123456789',
                'direccion' => 'Av. Industrial 458, Lima',
                'telefono' => '01-4567890',
                'email' => 'ventas@petpremium.com',
                'contacto' => 'Roberto Silva',
                'activo' => true,
            ],
            [
                'nombre' => 'Distribuidora Mascota Feliz',
                'ruc' => '20234567890',
                'direccion' => 'Jr. Los Alimentos 234, Callao',
                'telefono' => '01-5678901',
                'email' => 'contacto@mascotafeliz.com',
                'contacto' => 'Carmen Pérez',
                'activo' => true,
            ],
            [
                'nombre' => 'Juguetes y Accesorios Pet E.I.R.L.',
                'ruc' => '20345678901',
                'direccion' => 'Calle Los Pinos 567, San Isidro',
                'telefono' => '01-6789012',
                'email' => 'ventas@juguetespet.com',
                'contacto' => 'Luis Mendoza',
                'activo' => true,
            ],
            [
                'nombre' => 'Higiene Pet Care',
                'ruc' => '20456789012',
                'direccion' => 'Av. La Marina 890, Pueblo Libre',
                'telefono' => '01-7890123',
                'email' => 'info@higienepet.com',
                'contacto' => 'Diana Torres',
                'activo' => true,
            ],
            [
                'nombre' => 'Veterinaria Global Import',
                'ruc' => '20567890123',
                'direccion' => 'Calle Los Médicos 123, Miraflores',
                'telefono' => '01-8901234',
                'email' => 'ventas@vetglobal.com',
                'contacto' => 'Dr. Mario Vargas',
                'activo' => true,
            ],
            [
                'nombre' => 'Acuarios del Pacífico S.A.C.',
                'ruc' => '20678901234',
                'direccion' => 'Av. Marina 456, La Perla',
                'telefono' => '01-9012345',
                'email' => 'contacto@acuariospacifico.com',
                'contacto' => 'Jorge Palacios',
                'activo' => true,
            ],
            [
                'nombre' => 'Proveedor Inactivo Test',
                'ruc' => '20789012345',
                'direccion' => 'Calle Test 999',
                'telefono' => '01-0123456',
                'email' => 'test@inactivo.com',
                'contacto' => 'Test User',
                'activo' => false,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
