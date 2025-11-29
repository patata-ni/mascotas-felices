<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TiendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas públicas de la tienda (sin autenticación requerida)
Route::get('/', [TiendaController::class, 'index'])->name('tienda.index');
Route::get('/tienda', [TiendaController::class, 'index']);
Route::get('/tienda/carrito', [TiendaController::class, 'carrito'])->name('tienda.carrito');
Route::post('/tienda/checkout', [TiendaController::class, 'checkout'])->name('tienda.checkout');
Route::get('/tienda/comprobante/{id}', [TiendaController::class, 'comprobante'])->name('tienda.comprobante');

// Rutas de autenticación de clientes
Route::get('/tienda/login', [TiendaController::class, 'login'])->name('tienda.login');
Route::post('/tienda/login', [TiendaController::class, 'loginPost'])->name('tienda.login.post');
Route::get('/tienda/registro', [TiendaController::class, 'registro'])->name('tienda.registro');
Route::post('/tienda/registro', [TiendaController::class, 'registroPost'])->name('tienda.registro.post');
Route::post('/tienda/logout', [TiendaController::class, 'logout'])->name('tienda.logout');
Route::get('/tienda/perfil', [TiendaController::class, 'perfil'])->name('tienda.perfil');

// Rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Acceso para todos los roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Productos - Acceso para administrador e inventario
    Route::middleware(['role:administrador,inventario'])->group(function () {
        Route::resource('productos', ProductoController::class)->parameters([
            'productos' => 'producto'
        ]);
        Route::get('productos/stock/bajo', [ProductoController::class, 'stockBajo'])->name('productos.stock-bajo');
    });

    // Categorías - Acceso para administrador e inventario
    Route::middleware(['role:administrador,inventario'])->group(function () {
        Route::resource('categorias', CategoriaController::class)->parameters([
            'categorias' => 'categoria'
        ]);
    });

    // Clientes - Acceso para administrador y vendedor
    Route::middleware(['role:administrador,vendedor'])->group(function () {
        Route::resource('clientes', ClienteController::class);
        Route::get('clientes/{cliente}/historial', [ClienteController::class, 'historial'])->name('clientes.historial');
    });

    // Proveedores - Acceso para administrador e inventario
    Route::middleware(['role:administrador,inventario'])->group(function () {
        Route::resource('proveedores', ProveedorController::class)->parameters([
            'proveedores' => 'proveedor'
        ]);
    });

    // Pedidos - Acceso para administrador e inventario
    Route::middleware(['role:administrador,inventario'])->group(function () {
        Route::resource('pedidos', PedidoController::class);
        Route::post('pedidos/{pedido}/recibir', [PedidoController::class, 'recibir'])->name('pedidos.recibir');
        Route::post('pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');
    });

    // Ventas - Acceso para administrador y vendedor
    Route::middleware(['role:administrador,vendedor'])->group(function () {
        Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
        Route::post('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular')->middleware('role:administrador');
        Route::get('ventas/{venta}/imprimir', [VentaController::class, 'imprimir'])->name('ventas.imprimir');
    });

    // Reportes - Acceso solo para administrador
    Route::middleware(['role:administrador'])->prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('productos-mas-vendidos', [ReporteController::class, 'productosMasVendidos'])->name('productos-mas-vendidos');
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('clientes');
        Route::get('pedidos', [ReporteController::class, 'pedidos'])->name('pedidos');
        Route::get('financiero', [ReporteController::class, 'financiero'])->name('financiero');
    });
});

require __DIR__.'/auth.php';
