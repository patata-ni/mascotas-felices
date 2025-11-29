<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Dashboard de reportes
     */
    public function index()
    {
        // Estadísticas generales del mes actual
        $ventasMes = Venta::where('estado', 'completada')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $productosStock = Producto::where('activo', true)->count();
        $stockBajo = Producto::whereRaw('stock_actual <= stock_minimo')->count();

        $clientesActivos = Cliente::whereHas('ventas', function($query) {
            $query->whereMonth('created_at', now()->month);
        })->count();

        $clientesNuevos = Cliente::whereMonth('created_at', now()->month)->count();

        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $montoPendiente = Pedido::where('estado', 'pendiente')->sum('total');

        return view('reportes.index', compact(
            'ventasMes',
            'productosStock',
            'stockBajo',
            'clientesActivos',
            'clientesNuevos',
            'pedidosPendientes',
            'montoPendiente'
        ));
    }

    /**
     * Reporte de ventas
     */
    public function ventas(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));

        $query = Venta::with(['cliente', 'usuario', 'detalles'])->where('estado', 'completada');

        // Filtros
        $query->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
        
        if ($request->has('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        // Para estadísticas usamos todas
        $todasVentas = $query->get();

        // Totales
        $totalVentas = $todasVentas->sum('total');
        $cantidadVentas = $todasVentas->count();
        $promedioVenta = $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0;

        // Productos vendidos
        $productosVendidos = 0;
        foreach ($todasVentas as $venta) {
            $productosVendidos += $venta->detalles->sum('cantidad');
        }

        // Ventas por método de pago
        $ventasPorMetodo = $todasVentas->groupBy('metodo_pago')->map(function ($items, $key) {
            return [
                'metodo' => ucfirst(str_replace('_', '/', $key)),
                'total' => $items->sum('total')
            ];
        })->values();

        // Ventas por día
        $ventasPorDia = $todasVentas->groupBy(function($venta) {
            return $venta->created_at->format('Y-m-d');
        })->map(function($items, $fecha) {
            return [
                'fecha' => \Carbon\Carbon::parse($fecha)->format('d/m'),
                'total' => $items->sum('total')
            ];
        })->values();

        // Para la tabla, paginamos
        $ventas = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('reportes.ventas', compact(
            'ventas',
            'totalVentas',
            'cantidadVentas',
            'promedioVenta',
            'productosVendidos',
            'ventasPorMetodo',
            'ventasPorDia'
        ));
    }

    /**
     * Reporte de inventario
     */
    public function inventario(Request $request)
    {
        $query = Producto::with(['categoria', 'proveedor']);

        // Filtros
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }
        if ($request->has('estado') && $request->estado != '') {
            if ($request->estado == 'sin_stock') {
                $query->where('stock_actual', 0);
            } elseif ($request->estado == 'bajo') {
                $query->whereRaw('stock_actual <= stock_minimo AND stock_actual > 0');
            } elseif ($request->estado == 'en_stock') {
                $query->whereRaw('stock_actual > stock_minimo');
            }
        }

        // Ordenamiento
        if ($request->has('orden')) {
            switch ($request->orden) {
                case 'stock_asc':
                    $query->orderBy('stock_actual', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('stock_actual', 'desc');
                    break;
                case 'valor':
                    $query->orderByRaw('(stock_actual * precio_venta) DESC');
                    break;
                default:
                    $query->orderBy('nombre');
            }
        } else {
            $query->orderBy('nombre');
        }

        $productos = $query->paginate(20);
        $categorias = \App\Models\Categoria::where('activo', true)->get();

        // Estadísticas
        $totalProductos = Producto::count();
        $productosEnStock = Producto::where('stock_actual', '>', 0)->count();
        $stockBajo = Producto::whereRaw('stock_actual <= stock_minimo AND stock_actual > 0')->count();
        $sinStock = Producto::where('stock_actual', 0)->count();

        $costoTotal = Producto::selectRaw('SUM(stock_actual * precio_compra) as total')->value('total') ?? 0;
        $valorVenta = Producto::selectRaw('SUM(stock_actual * precio_venta) as total')->value('total') ?? 0;
        $gananciaPotencial = $valorVenta - $costoTotal;
        $margenPromedio = $valorVenta > 0 ? ($gananciaPotencial / $valorVenta) * 100 : 0;

        // Inventario por categoría
        $inventarioPorCategoria = \App\Models\Categoria::with('productos')
            ->get()
            ->map(function($cat) {
                return [
                    'nombre' => $cat->nombre,
                    'cantidad' => $cat->productos->sum('stock_actual'),
                    'valor' => $cat->productos->sum(function($p) {
                        return $p->stock_actual * $p->precio_venta;
                    })
                ];
            });

        return view('reportes.inventario', compact(
            'productos',
            'categorias',
            'totalProductos',
            'productosEnStock',
            'stockBajo',
            'sinStock',
            'costoTotal',
            'valorVenta',
            'gananciaPotencial',
            'margenPromedio',
            'inventarioPorCategoria'
        ));
    }

    /**
     * Reporte de productos más vendidos
     */
    public function productosMasVendidos(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaHasta = $request->get('fecha_hasta', now()->format('Y-m-d'));
        $categoriaId = $request->get('categoria');

        $query = Producto::with(['categoria'])
            ->join('venta_detalles', 'productos.id', '=', 'venta_detalles.producto_id')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->where('ventas.estado', 'completada')
            ->whereBetween('ventas.created_at', [$fechaDesde, $fechaHasta]);

        if ($categoriaId) {
            $query->where('productos.categoria_id', $categoriaId);
        }

        $topProductos = $query->selectRaw('productos.*, 
            SUM(venta_detalles.cantidad) as total_vendido,
            SUM(venta_detalles.subtotal) as total_ingresos,
            AVG(venta_detalles.precio_unitario) as precio_promedio')
            ->groupBy('productos.id')
            ->orderByDesc('total_vendido')
            ->get();

        // Calcular porcentajes
        $totalVentas = $topProductos->sum('total_vendido');
        $topProductos = $topProductos->map(function($producto) use ($totalVentas) {
            $producto->porcentaje = $totalVentas > 0 ? ($producto->total_vendido / $totalVentas) * 100 : 0;
            return $producto;
        });

        $categorias = \App\Models\Categoria::where('activo', true)->get();

        return view('reportes.productos-mas-vendidos', compact('topProductos', 'categorias'));
    }

    /**
     * Reporte de clientes
     */
    public function clientes(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        
        $query = Cliente::withCount(['ventas' => function($q) {
                $q->where('estado', 'completada');
            }])
            ->withSum(['ventas' => function($q) {
                $q->where('estado', 'completada');
            }], 'total')
            ->withMax(['ventas' => function($q) {
                $q->where('estado', 'completada');
            }], 'created_at');

        // Filtros
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Ordenamiento
        if ($request->has('orden')) {
            switch ($request->orden) {
                case 'compras':
                    $query->orderBy('ventas_count', 'desc');
                    break;
                case 'monto':
                    $query->orderBy('ventas_sum_total', 'desc');
                    break;
                case 'puntos':
                    $query->orderBy('puntos_fidelidad', 'desc');
                    break;
                case 'reciente':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('nombre');
            }
        } else {
            $query->orderBy('nombre');
        }

        $clientes = $query->paginate(15);

        // Estadísticas
        $totalClientes = Cliente::count();
        $clientesActivos = Cliente::whereHas('ventas', function($q) use ($fechaDesde) {
            $q->where('estado', 'completada')
              ->where('created_at', '>=', $fechaDesde);
        })->count();
        $clientesNuevos = Cliente::whereMonth('created_at', now()->month)->count();
        $puntosTotal = Cliente::sum('puntos_fidelidad');

        // Top 5 clientes
        $topClientes = Cliente::withCount(['ventas' => function($q) {
                $q->where('estado', 'completada');
            }])
            ->withSum(['ventas' => function($q) {
                $q->where('estado', 'completada');
            }], 'total')
            ->having('ventas_count', '>', 0)
            ->orderBy('ventas_sum_total', 'desc')
            ->limit(5)
            ->get()
            ->map(function($cliente) {
                $cliente->total_compras = $cliente->ventas_count;
                $cliente->total_gastado = $cliente->ventas_sum_total ?? 0;
                return $cliente;
            });

        // Clientes por mes
        $clientesPorMes = Cliente::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Distribución por compras
        $sinCompras = Cliente::doesntHave('ventas')->count();
        $compras1a3 = Cliente::withCount('ventas')->having('ventas_count', '>=', 1)->having('ventas_count', '<=', 3)->count();
        $compras4a10 = Cliente::withCount('ventas')->having('ventas_count', '>=', 4)->having('ventas_count', '<=', 10)->count();
        $comprasMas10 = Cliente::withCount('ventas')->having('ventas_count', '>', 10)->count();
        $distribucionCompras = [$sinCompras, $compras1a3, $compras4a10, $comprasMas10];

        return view('reportes.clientes', compact(
            'clientes',
            'totalClientes',
            'clientesActivos',
            'clientesNuevos',
            'puntosTotal',
            'topClientes',
            'clientesPorMes',
            'distribucionCompras'
        ));
    }

    /**
     * Reporte de pedidos
     */
    public function pedidos(Request $request)
    {
        $query = Pedido::with(['proveedor', 'usuario']);

        // Filtros
        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->where('fecha_pedido', '>=', $request->fecha_inicio);
        }
        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $query->where('fecha_pedido', '<=', $request->fecha_fin);
        }
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        // Para estadísticas usamos todos los pedidos
        $todosPedidos = $query->get();

        // Estadísticas
        $totalPedidos = $todosPedidos->sum('total');
        $cantidadPedidos = $todosPedidos->count();
        $pedidosPendientes = $todosPedidos->whereIn('estado', ['pendiente', 'confirmado', 'enviado'])->count();
        $pedidosRecibidos = $todosPedidos->where('estado', 'recibido')->count();
        $pedidosCancelados = $todosPedidos->where('estado', 'cancelado')->count();

        // Para la tabla, paginamos
        $pedidos = $query->orderBy('fecha_pedido', 'desc')->paginate(15);

        // Pedidos por proveedor
        $pedidosPorProveedor = Pedido::with('proveedor')
            ->selectRaw('proveedor_id, COUNT(*) as total_pedidos, SUM(total) as monto_total, SUM(CASE WHEN estado = "pendiente" THEN 1 ELSE 0 END) as pendientes')
            ->groupBy('proveedor_id')
            ->get();

        // Pedidos por mes
        $pedidosPorMes = Pedido::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total, SUM(total) as monto')
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get();

        $montoTotal = $totalPedidos;
        $proveedores = \App\Models\Proveedor::where('activo', true)->get();

        return view('reportes.pedidos', compact(
            'pedidos',
            'totalPedidos',
            'cantidadPedidos',
            'pedidosPendientes',
            'pedidosRecibidos',
            'pedidosCancelados',
            'montoTotal',
            'proveedores',
            'pedidosPorProveedor',
            'pedidosPorMes'
        ));
    }

    /**
     * Reporte financiero
     */
    public function financiero(Request $request)
    {
        $fechaInicio = $request->get('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_hasta', now()->format('Y-m-d'));

        // Ingresos (ventas completadas)
        $ventas = Venta::with('detalles.producto')
            ->where('estado', 'completada')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->get();

        $totalIngresos = $ventas->sum('total');
        $cantidadVentas = $ventas->count();

        // Calcular costos basados en precio de compra de productos vendidos
        $totalCostos = 0;
        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                $totalCostos += $detalle->producto->precio_compra * $detalle->cantidad;
            }
        }

        $utilidadBruta = $totalIngresos - $totalCostos;
        $margenUtilidad = $totalIngresos > 0 ? ($utilidadBruta / $totalIngresos) * 100 : 0;

        // Inversión en pedidos
        $inversionPedidos = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->sum('total');
        $cantidadPedidos = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])->count();

        // Ingresos por método de pago
        $ingresosPorMetodo = Venta::where('estado', 'completada')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->selectRaw('metodo_pago, SUM(total) as total, COUNT(*) as cantidad')
            ->groupBy('metodo_pago')
            ->get();

        // Promedios
        $ticketPromedio = $cantidadVentas > 0 ? $totalIngresos / $cantidadVentas : 0;
        $costoPromedio = $cantidadVentas > 0 ? $totalCostos / $cantidadVentas : 0;
        $utilidadPromedio = $cantidadVentas > 0 ? $utilidadBruta / $cantidadVentas : 0;

        // Productos más rentables
        $productosRentables = Producto::with('categoria')
            ->join('venta_detalles', 'productos.id', '=', 'venta_detalles.producto_id')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->where('ventas.estado', 'completada')
            ->whereBetween('ventas.created_at', [$fechaInicio, $fechaFin])
            ->selectRaw('productos.*, 
                SUM(venta_detalles.cantidad) as total_vendido,
                SUM(venta_detalles.subtotal) as total_ingresos,
                SUM(venta_detalles.cantidad * productos.precio_compra) as total_costos,
                (SUM(venta_detalles.subtotal) - SUM(venta_detalles.cantidad * productos.precio_compra)) as utilidad,
                ((SUM(venta_detalles.subtotal) - SUM(venta_detalles.cantidad * productos.precio_compra)) / SUM(venta_detalles.subtotal) * 100) as margen')
            ->groupBy('productos.id')
            ->orderByDesc('utilidad')
            ->limit(10)
            ->get();

        // Evolución mensual
        $evolucionMensual = DB::table('ventas')
            ->where('estado', 'completada')
            ->whereBetween('created_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, 
                SUM(total) as ingresos,
                0 as costos,
                0 as utilidad')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                // Calcular costos para cada mes
                $ventas = Venta::with('detalles.producto')
                    ->where('estado', 'completada')
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$item->mes])
                    ->get();
                
                $costos = 0;
                foreach ($ventas as $venta) {
                    foreach ($venta->detalles as $detalle) {
                        $costos += $detalle->producto->precio_compra * $detalle->cantidad;
                    }
                }
                
                $item->costos = $costos;
                $item->utilidad = $item->ingresos - $costos;
                return $item;
            });

        return view('reportes.financiero', compact(
            'totalIngresos',
            'totalCostos',
            'utilidadBruta',
            'margenUtilidad',
            'inversionPedidos',
            'cantidadPedidos',
            'cantidadVentas',
            'ingresosPorMetodo',
            'ticketPromedio',
            'costoPromedio',
            'utilidadPromedio',
            'productosRentables',
            'evolucionMensual'
        ));
    }
}
