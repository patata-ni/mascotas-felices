<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'contacto_nombre',
        'contacto_telefono',
        'evaluacion',
        'notas',
        'activo'
    ];

    protected $casts = [
        'evaluacion' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Relación uno a muchos con productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Relación uno a muchos con pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
