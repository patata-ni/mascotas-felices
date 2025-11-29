<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'documento',
        'tipo_documento',
        'telefono',
        'email',
        'password',
        'direccion',
        'fecha_nacimiento',
        'puntos_fidelidad',
        'total_compras',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'puntos_fidelidad' => 'integer',
        'total_compras' => 'decimal:2',
        'activo' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Relación uno a muchos con ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Obtiene el historial de compras del cliente
     */
    public function historialCompras()
    {
        return $this->ventas()
            ->with('detalles.producto')
            ->orderBy('fecha_venta', 'desc')
            ->get();
    }

    /**
     * Calcula la cantidad de compras realizadas
     */
    public function cantidadCompras()
    {
        return $this->ventas()->where('estado', 'completada')->count();
    }
}
