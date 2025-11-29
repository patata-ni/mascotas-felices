<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_pedido',
        'proveedor_id',
        'usuario_id',
        'fecha_pedido',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'estado',
        'subtotal',
        'impuesto',
        'total',
        'notas'
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega_estimada' => 'date',
        'fecha_entrega_real' => 'date',
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación muchos a uno con proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación muchos a uno con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación uno a muchos con detalles del pedido
     */
    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    /**
     * Genera un número de pedido automático
     */
    public static function generarNumeroPedido()
    {
        $ultimo = self::latest('id')->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        return 'PED-' . date('Ymd') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula el total del pedido
     */
    public function calcularTotal()
    {
        $subtotal = $this->detalles->sum('subtotal');
        $this->subtotal = $subtotal;
        $this->impuesto = $subtotal * 0.18; // IGV 18%
        $this->total = $subtotal + $this->impuesto;
        $this->save();
    }
}
