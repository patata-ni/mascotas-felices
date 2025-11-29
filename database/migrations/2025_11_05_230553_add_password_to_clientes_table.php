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
        Schema::table('clientes', function (Blueprint $table) {
            // Agregar campo de contraseña
            $table->string('password')->after('email');
            
            // Hacer el email único y no nullable
            $table->string('email', 100)->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('password');
            
            // Revertir cambios en email
            $table->string('email', 100)->nullable()->change();
        });
    }
};
