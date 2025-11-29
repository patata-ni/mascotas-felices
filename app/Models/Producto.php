<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'proveedor_id',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'unidad_medida',
        'imagen',
        'activo'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'stock_maximo' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Relación muchos a uno con categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación muchos a uno con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación muchos a muchos con pedidos
     */
    public function pedidoDetalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    /**
     * Relación muchos a muchos con ventas
     */
    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    /**
     * Verifica si el stock está bajo
     */
    public function stockBajo()
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    /**
     * Calcula el margen de ganancia
     */
    public function margenGanancia()
    {
        if ($this->precio_compra == 0) return 0;
        return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
    }

    /**
     * Accessor para 'stock' (alias de stock_actual)
     */
    public function getStockAttribute()
    {
        return $this->stock_actual;
    }

    /**
     * Mutator para 'stock' (alias de stock_actual)
     */
    public function setStockAttribute($value)
    {
        $this->attributes['stock_actual'] = $value;
    }

    /**
     * Accessor para 'precio' (alias de precio_venta)
     */
    public function getPrecioAttribute()
    {
        return $this->precio_venta;
    }

    /**
     * Mutator para 'precio' (alias de precio_venta)
     */
    public function setPrecioAttribute($value)
    {
        $this->attributes['precio_venta'] = $value;
    }
}
