<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    /**
     * Relación uno a muchos con pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Relación uno a muchos con ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function esAdministrador()
    {
        return $this->role === 'administrador';
    }

    /**
     * Verifica si el usuario es vendedor
     */
    public function esVendedor()
    {
        return $this->role === 'vendedor';
    }

    /**
     * Verifica si el usuario gestiona inventario
     */
    public function esInventario()
    {
        return $this->role === 'inventario';
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    public function tieneRol($rol)
    {
        return $this->role === $rol;
    }
}
