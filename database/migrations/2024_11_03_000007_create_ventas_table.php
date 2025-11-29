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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_venta', 50)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->date('fecha_venta');
            $table->enum('tipo_venta', ['tienda', 'online'])->default('tienda');
            $table->enum('tipo_comprobante', ['boleta', 'factura', 'ticket'])->default('boleta');
            $table->string('numero_comprobante', 50)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'yape', 'plin'])->default('efectivo');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('puntos_otorgados')->default(0);
            $table->enum('estado', ['completada', 'anulada'])->default('completada');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
