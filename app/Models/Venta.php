<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_venta',
        'cliente_id',
        'usuario_id',
        'fecha_venta',
        'tipo_venta',
        'tipo_comprobante',
        'numero_comprobante',
        'metodo_pago',
        'subtotal',
        'descuento',
        'impuesto',
        'total',
        'puntos_otorgados',
        'estado',
        'notas'
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
        'puntos_otorgados' => 'integer',
    ];

    /**
     * Relación muchos a uno con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación muchos a uno con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación uno a muchos con detalles de venta
     */
    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    /**
     * Genera un número de venta automático
     */
    public static function generarNumeroVenta()
    {
        $ultimo = self::latest('id')->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        return 'VEN-' . date('Ymd') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula el total de la venta
     */
    public function calcularTotal()
    {
        $subtotal = $this->detalles->sum('subtotal');
        $this->subtotal = $subtotal;
        $this->impuesto = 0; // Sin IGV
        $this->total = $subtotal - $this->descuento;
        
        // Calcular puntos de fidelidad (1 punto por cada MX$100)
        $this->puntos_otorgados = floor($this->total / 100);
        
        $this->save();
    }

    /**
     * Actualiza el stock de productos al completar la venta
     */
    public function actualizarStock()
    {
        foreach ($this->detalles as $detalle) {
            $producto = $detalle->producto;
            $producto->stock_actual -= $detalle->cantidad;
            $producto->save();
        }
    }

    /**
     * Actualiza los puntos del cliente
     */
    public function actualizarPuntosCliente()
    {
        if ($this->cliente_id && $this->estado == 'completada') {
            $cliente = $this->cliente;
            $cliente->puntos_fidelidad += $this->puntos_otorgados;
            $cliente->total_compras += $this->total;
            $cliente->save();
        }
    }

    /**
     * Accessor para igv (alias de impuesto)
     */
    public function getIgvAttribute()
    {
        return $this->impuesto;
    }
}
