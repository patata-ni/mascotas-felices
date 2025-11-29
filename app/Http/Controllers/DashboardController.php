<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Variables comunes
        $data = [];
        
        // VENDEDOR: Solo métricas de ventas y clientes
        if ($user->esVendedor()) {
            $data['totalClientes'] = Cliente::where('activo', true)->count();
            
            // Ventas del mes actual
            $data['ventasMes'] = Venta::where('estado', 'completada')
                ->whereMonth('fecha_venta', date('m'))
                ->whereYear('fecha_venta', date('Y'))
                ->sum('total');
            
            // Ventas de hoy
            $data['ventasHoy'] = Venta::where('estado', 'completada')
                ->whereDate('fecha_venta', today())
                ->sum('total');
            
            // Cantidad de ventas de hoy
            $data['cantidadVentasHoy'] = Venta::where('estado', 'completada')
                ->whereDate('fecha_venta', today())
                ->count();
            
            // Ventas últimos 7 días
            $data['ventasUltimos7Dias'] = Venta::where('estado', 'completada')
                ->where('fecha_venta', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(total) as total'))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
            
            // Últimas ventas
            $data['ultimasVentas'] = Venta::with(['cliente', 'usuario'])
                ->where('estado', 'completada')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Productos más vendidos (para información)
            $data['productosMasVendidos'] = DB::table('venta_detalles')
                ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->where('ventas.estado', 'completada')
                ->where('ventas.fecha_venta', '>=', now()->subDays(30))
                ->select('productos.nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                ->groupBy('productos.id', 'productos.nombre')
                ->orderByDesc('total_vendido')
                ->limit(10)
                ->get();
                
        // INVENTARIO: Solo métricas de productos, stock y pedidos
        } elseif ($user->esInventario()) {
            $data['totalProductos'] = Producto::where('activo', true)->count();
            $data['stockBajo'] = Producto::whereRaw('stock_actual <= stock_minimo')->count();
            $data['pedidosPendientes'] = Pedido::whereIn('estado', ['pendiente', 'confirmado', 'enviado'])->count();
            
            // Productos con stock bajo
            $data['productosStockBajo'] = Producto::with('categoria')
                ->whereRaw('stock_actual <= stock_minimo')
                ->orderBy('stock_actual')
                ->limit(10)
                ->get();
            
            // Últimos pedidos
            $data['ultimosPedidos'] = Pedido::with(['proveedor'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Productos más vendidos (para reposición)
            $data['productosMasVendidos'] = DB::table('venta_detalles')
                ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->where('ventas.estado', 'completada')
                ->where('ventas.fecha_venta', '>=', now()->subDays(30))
                ->select('productos.nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                ->groupBy('productos.id', 'productos.nombre')
                ->orderByDesc('total_vendido')
                ->limit(10)
                ->get();
                
        // ADMINISTRADOR: Vista completa
        } else {
            $data['totalProductos'] = Producto::where('activo', true)->count();
            $data['totalClientes'] = Cliente::where('activo', true)->count();
            $data['stockBajo'] = Producto::whereRaw('stock_actual <= stock_minimo')->count();
            
            // Ventas del mes actual
            $data['ventasMes'] = Venta::where('estado', 'completada')
                ->whereMonth('fecha_venta', date('m'))
                ->whereYear('fecha_venta', date('Y'))
                ->sum('total');
            
            // Ventas de hoy
            $data['ventasHoy'] = Venta::where('estado', 'completada')
                ->whereDate('fecha_venta', today())
                ->sum('total');
            
            // Cantidad de ventas de hoy
            $data['cantidadVentasHoy'] = Venta::where('estado', 'completada')
                ->whereDate('fecha_venta', today())
                ->count();
            
            // Pedidos pendientes
            $data['pedidosPendientes'] = Pedido::whereIn('estado', ['pendiente', 'confirmado', 'enviado'])->count();
            
            // Productos más vendidos (últimos 30 días)
            $data['productosMasVendidos'] = DB::table('venta_detalles')
                ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->where('ventas.estado', 'completada')
                ->where('ventas.fecha_venta', '>=', now()->subDays(30))
                ->select('productos.nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                ->groupBy('productos.id', 'productos.nombre')
                ->orderByDesc('total_vendido')
                ->limit(10)
                ->get();
            
            // Ventas últimos 7 días
            $data['ventasUltimos7Dias'] = Venta::where('estado', 'completada')
                ->where('fecha_venta', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(total) as total'))
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();
            
            // Últimas ventas
            $data['ultimasVentas'] = Venta::with(['cliente', 'usuario'])
                ->where('estado', 'completada')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Productos con stock bajo
            $data['productosStockBajo'] = Producto::with('categoria')
                ->whereRaw('stock_actual <= stock_minimo')
                ->orderBy('stock_actual')
                ->limit(10)
                ->get();
        }

        return view('dashboard', $data);
    }
}
