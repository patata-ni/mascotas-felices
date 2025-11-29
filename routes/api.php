<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\VentaApiController;
use App\Http\Controllers\Api\PedidoApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);

    // Productos
    Route::apiResource('productos', ProductoApiController::class);
    Route::get('productos/stock/bajo', [ProductoApiController::class, 'stockBajo']);
    
    // Clientes
    Route::apiResource('clientes', ClienteApiController::class);
    Route::get('clientes/{cliente}/historial', [ClienteApiController::class, 'historial']);
    
    // Ventas
    Route::apiResource('ventas', VentaApiController::class);
    Route::post('ventas/{venta}/anular', [VentaApiController::class, 'anular']);
    
    // Pedidos
    Route::apiResource('pedidos', PedidoApiController::class);
    Route::post('pedidos/{pedido}/recibir', [PedidoApiController::class, 'recibir']);
    Route::post('pedidos/{pedido}/cancelar', [PedidoApiController::class, 'cancelar']);
    
    // Estadísticas
    Route::get('dashboard/stats', [AuthController::class, 'stats']);
    
    // Sincronización
    Route::post('sync', [AuthController::class, 'sync']);
});
