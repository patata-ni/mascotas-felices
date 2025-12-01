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
        DB::statement("ALTER TABLE clientes DROP CONSTRAINT IF EXISTS clientes_tipo_documento_check");
        DB::statement("ALTER TABLE clientes ADD CONSTRAINT clientes_tipo_documento_check CHECK (tipo_documento IN ('INE', 'RFC', 'CURP', 'PASAPORTE'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE clientes DROP CONSTRAINT IF EXISTS clientes_tipo_documento_check");
        DB::statement("ALTER TABLE clientes ADD CONSTRAINT clientes_tipo_documento_check CHECK (tipo_documento IN ('DNI', 'RUC', 'CE', 'PASAPORTE'))");
    }
};
