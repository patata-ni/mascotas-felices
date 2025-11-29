<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero expandir el enum para incluir todos los valores
        DB::statement("ALTER TABLE clientes MODIFY COLUMN tipo_documento ENUM('DNI', 'RUC', 'CE', 'PASAPORTE', 'INE', 'CURP', 'RFC') NOT NULL DEFAULT 'DNI'");
        
        // Actualizar valores existentes
        DB::statement("UPDATE clientes SET tipo_documento = 'INE' WHERE tipo_documento = 'DNI'");
        DB::statement("UPDATE clientes SET tipo_documento = 'RFC' WHERE tipo_documento = 'RUC'");
        DB::statement("UPDATE clientes SET tipo_documento = 'INE' WHERE tipo_documento = 'CE'");
        
        // Ahora cambiar el enum solo a tipos mexicanos
        DB::statement("ALTER TABLE clientes MODIFY COLUMN tipo_documento ENUM('INE', 'CURP', 'RFC', 'PASAPORTE') NOT NULL DEFAULT 'INE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("UPDATE clientes SET tipo_documento = 'DNI' WHERE tipo_documento = 'INE'");
        DB::statement("UPDATE clientes SET tipo_documento = 'RUC' WHERE tipo_documento = 'RFC'");
        
        DB::statement("ALTER TABLE clientes MODIFY COLUMN tipo_documento ENUM('DNI', 'RUC', 'CE', 'PASAPORTE') NOT NULL DEFAULT 'DNI'");
    }
};
