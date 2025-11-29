@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Bienvenido, {{ Auth::user()->name }} 
        <span class="text-sm bg-[#EDE9FE] text-[#190C7B] px-3 py-1 rounded-full ml-2">
            {{ ucfirst(Auth::user()->role) }}
        </span>
    </p>
</div>

@if(Auth::user()->esVendedor())
    {{-- DASHBOARD VENDEDOR --}}
    <!-- Tarjetas de Estadísticas Vendedor -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Ventas de Hoy -->
        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Ventas de Hoy</p>
                    <h3 class="text-3xl font-bold">$ {{ number_format($ventasHoy, 2) }}</h3>
                    <p class="text-blue-100 text-xs mt-2">{{ $cantidadVentasHoy }} ventas realizadas</p>
                </div>
                <div class="bg-[#5B8FCC] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-cash-register text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#F3E8FF] text-sm mb-1">Ventas del Mes</p>
                    <h3 class="text-3xl font-bold">$ {{ number_format($ventasMes, 2) }}</h3>
                    <p class="text-[#F3E8FF] text-xs mt-2">{{ date('F Y') }}</p>
                </div>
                <div class="bg-[#9B8AC4] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#F3E8FF] text-sm mb-1">Clientes Activos</p>
                    <h3 class="text-3xl font-bold">{{ $totalClientes }}</h3>
                    <p class="text-[#F3E8FF] text-xs mt-2">Base de datos</p>
                </div>
                <div class="bg-[#9B8AC4] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Vendedor -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Ventas Últimos 7 Días -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-[#190C7B] mr-2"></i>
                Ventas Últimos 7 Días
            </h2>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($ventasUltimos7Dias as $venta)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-[#4A3DB8] rounded-t hover:bg-[#190C7B] transition-colors relative group"
                         style="height: {{ $ventasUltimos7Dias->max('total') > 0 ? ($venta->total / $ventasUltimos7Dias->max('total')) * 100 : 0 }}%">
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            $ {{ number_format($venta->total, 2) }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-[#E89A7B] mr-2"></i>
                Productos Más Vendidos (30 días)
            </h2>
            <div class="space-y-3">
                @forelse($productosMasVendidos->take(5) as $producto)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $producto->total_vendido }} unidades</p>
                    </div>
                    <div class="w-32 bg-gray-200 rounded-full h-2 ml-4">
                        <div class="bg-[#E89A7B] h-2 rounded-full" 
                             style="width: {{ $productosMasVendidos->max('total_vendido') > 0 ? ($producto->total_vendido / $productosMasVendidos->max('total_vendido')) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimas Ventas Vendedor -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-shopping-cart text-[#5B8FCC] mr-2"></i>
                Últimas Ventas
            </h2>
            <a href="{{ route('ventas.index') }}" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">N° Venta</th>
                        <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">Cliente</th>
                        <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">Fecha</th>
                        <th class="text-right py-2 px-3 text-sm font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasVentas as $venta)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-3">
                            <a href="{{ route('ventas.show', $venta->id) }}" 
                               class="text-[#190C7B] hover:underline text-sm">
                                {{ $venta->numero_venta }}
                            </a>
                        </td>
                        <td class="py-3 px-3 text-sm">
                            {{ $venta->cliente ? $venta->cliente->nombre : 'Cliente Anónimo' }}
                        </td>
                        <td class="py-3 px-3 text-sm text-gray-600">
                            {{ $venta->fecha_venta->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-3 text-right text-sm font-semibold text-gray-800">
                            $ {{ number_format($venta->total, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">No hay ventas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Accesos Rápidos Vendedor -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <a href="{{ route('ventas.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-cash-register text-4xl text-[#190C7B] mb-3"></i>
            <p class="font-semibold text-gray-800">Nueva Venta</p>
        </a>
        
        <a href="{{ route('clientes.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-user-plus text-4xl text-[#5B8FCC] mb-3"></i>
            <p class="font-semibold text-gray-800">Nuevo Cliente</p>
        </a>
        
        <a href="{{ route('clientes.index') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-users text-4xl text-[#8B7AB8] mb-3"></i>
            <p class="font-semibold text-gray-800">Ver Clientes</p>
        </a>
    </div>

@elseif(Auth::user()->esInventario())
    {{-- DASHBOARD INVENTARIO --}}
    <!-- Tarjetas de Estadísticas Inventario -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Productos Totales -->
        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Productos Activos</p>
                    <h3 class="text-3xl font-bold">{{ $totalProductos }}</h3>
                    <p class="text-blue-100 text-xs mt-2">En catálogo</p>
                </div>
                <div class="bg-[#5B8FCC] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-box text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Stock Bajo -->
        <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#FFF5F0] text-sm mb-1">Productos Stock Bajo</p>
                    <h3 class="text-3xl font-bold">{{ $stockBajo }}</h3>
                    <p class="text-[#FFF5F0] text-xs mt-2">Requieren atención</p>
                </div>
                <div class="bg-[#D68A6B] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#F3E8FF] text-sm mb-1">Pedidos Pendientes</p>
                    <h3 class="text-3xl font-bold">{{ $pedidosPendientes }}</h3>
                    <p class="text-[#F3E8FF] text-xs mt-2">Por recibir</p>
                </div>
                <div class="bg-[#9B8AC4] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-clipboard-list text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos con Stock Bajo -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-exclamation-triangle text-[#E89A7B] mr-2"></i>
                Productos con Stock Bajo
            </h2>
            <a href="{{ route('productos.stock-bajo') }}" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-3">
            @forelse($productosStockBajo->take(10) as $producto)
            <div class="flex items-center justify-between p-3 bg-[#FFF5F0] rounded-lg border border-[#E89A7B]">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                    <p class="text-sm text-gray-600">{{ $producto->categoria->nombre }}</p>
                </div>
                <div class="text-right mr-4">
                    <p class="text-lg font-bold text-[#E89A7B]">{{ $producto->stock_actual }}</p>
                    <p class="text-xs text-gray-600">Mín: {{ $producto->stock_minimo }}</p>
                </div>
                <a href="{{ route('pedidos.create') }}" 
                   class="bg-[#190C7B] text-white px-3 py-1 rounded text-sm hover:bg-[#2D1B9E]">
                    <i class="fas fa-plus mr-1"></i>Pedir
                </a>
            </div>
            @empty
            <div class="text-center py-4">
                <i class="fas fa-check-circle text-[#5B8FCC] text-3xl mb-2"></i>
                <p class="text-gray-600">Todos los productos tienen stock suficiente</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Últimos Pedidos y Productos Más Vendidos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Últimos Pedidos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-truck text-[#5B8FCC] mr-2"></i>
                    Últimos Pedidos
                </h2>
                <a href="{{ route('pedidos.index') }}" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">N° Pedido</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">Proveedor</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPedidos as $pedido)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-3">
                                <a href="{{ route('pedidos.show', $pedido->id) }}" 
                                   class="text-[#190C7B] hover:underline text-sm">
                                    {{ $pedido->numero_pedido }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-sm">
                                {{ $pedido->proveedor->nombre }}
                            </td>
                            <td class="py-3 px-3">
                                @if($pedido->estado == 'pendiente')
                                    <span class="text-xs bg-[#FFF5F0] text-[#E89A7B] px-2 py-1 rounded-full">Pendiente</span>
                                @elseif($pedido->estado == 'recibido')
                                    <span class="text-xs bg-[#EDE9FE] text-[#190C7B] px-2 py-1 rounded-full">Recibido</span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Cancelado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">No hay pedidos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-[#E89A7B] mr-2"></i>
                Productos Más Vendidos (30 días)
            </h2>
            <div class="space-y-3">
                @forelse($productosMasVendidos->take(8) as $producto)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $producto->total_vendido }} unidades</p>
                    </div>
                    <div class="w-32 bg-gray-200 rounded-full h-2 ml-4">
                        <div class="bg-[#8B7AB8] h-2 rounded-full" 
                             style="width: {{ $productosMasVendidos->max('total_vendido') > 0 ? ($producto->total_vendido / $productosMasVendidos->max('total_vendido')) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos Inventario -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('productos.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-plus-circle text-4xl text-[#190C7B] mb-3"></i>
            <p class="font-semibold text-gray-800">Nuevo Producto</p>
        </a>
        
        <a href="{{ route('pedidos.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-clipboard-list text-4xl text-[#5B8FCC] mb-3"></i>
            <p class="font-semibold text-gray-800">Nuevo Pedido</p>
        </a>
        
        <a href="{{ route('productos.stock-bajo') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-exclamation-triangle text-4xl text-[#E89A7B] mb-3"></i>
            <p class="font-semibold text-gray-800">Stock Bajo</p>
        </a>
        
        <a href="{{ route('categorias.index') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-tags text-4xl text-[#8B7AB8] mb-3"></i>
            <p class="font-semibold text-gray-800">Categorías</p>
        </a>
    </div>

@else
    {{-- DASHBOARD ADMINISTRADOR --}}
    <!-- Tarjetas de Estadísticas Completas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Ventas de Hoy -->
        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Ventas de Hoy</p>
                    <h3 class="text-3xl font-bold">$ {{ number_format($ventasHoy, 2) }}</h3>
                    <p class="text-blue-100 text-xs mt-2">{{ $cantidadVentasHoy }} ventas realizadas</p>
                </div>
                <div class="bg-[#5B8FCC] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-cash-register text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#F3E8FF] text-sm mb-1">Ventas del Mes</p>
                    <h3 class="text-3xl font-bold">$ {{ number_format($ventasMes, 2) }}</h3>
                    <p class="text-[#F3E8FF] text-xs mt-2">{{ date('F Y') }}</p>
                </div>
                <div class="bg-[#9B8AC4] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Productos Totales -->
        <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#F3E8FF] text-sm mb-1">Productos Activos</p>
                    <h3 class="text-3xl font-bold">{{ $totalProductos }}</h3>
                    <p class="text-[#F3E8FF] text-xs mt-2">
                        <span class="text-[#FFF5F0]">{{ $stockBajo }}</span> con stock bajo
                    </p>
                </div>
                <div class="bg-[#A78BFA] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-box text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[#FFF5F0] text-sm mb-1">Clientes Activos</p>
                    <h3 class="text-3xl font-bold">{{ $totalClientes }}</h3>
                    <p class="text-[#FFF5F0] text-xs mt-2">{{ $pedidosPendientes }} pedidos pendientes</p>
                </div>
                <div class="bg-[#D68A6B] bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Ventas Últimos 7 Días -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-[#190C7B] mr-2"></i>
                Ventas Últimos 7 Días
            </h2>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($ventasUltimos7Dias as $venta)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-[#4A3DB8] rounded-t hover:bg-[#190C7B] transition-colors relative group"
                         style="height: {{ $ventasUltimos7Dias->max('total') > 0 ? ($venta->total / $ventasUltimos7Dias->max('total')) * 100 : 0 }}%">
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            $ {{ number_format($venta->total, 2) }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-star text-[#E89A7B] mr-2"></i>
                Productos Más Vendidos (30 días)
            </h2>
            <div class="space-y-3">
                @forelse($productosMasVendidos->take(5) as $producto)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $producto->total_vendido }} unidades</p>
                    </div>
                    <div class="w-32 bg-gray-200 rounded-full h-2 ml-4">
                        <div class="bg-[#E89A7B] h-2 rounded-full" 
                             style="width: {{ $productosMasVendidos->max('total_vendido') > 0 ? ($producto->total_vendido / $productosMasVendidos->max('total_vendido')) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimas Ventas y Productos con Stock Bajo -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Últimas Ventas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-shopping-cart text-[#5B8FCC] mr-2"></i>
                    Últimas Ventas
                </h2>
                <a href="{{ route('ventas.index') }}" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">N° Venta</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-600">Cliente</th>
                            <th class="text-right py-2 px-3 text-sm font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasVentas as $venta)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-3">
                                <a href="{{ route('ventas.show', $venta->id) }}" 
                                   class="text-[#190C7B] hover:underline text-sm">
                                    {{ $venta->numero_venta }}
                                </a>
                            </td>
                            <td class="py-3 px-3 text-sm">
                                {{ $venta->cliente ? $venta->cliente->nombre : 'Cliente Anónimo' }}
                            </td>
                            <td class="py-3 px-3 text-right text-sm font-semibold text-gray-800">
                                $ {{ number_format($venta->total, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">No hay ventas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos con Stock Bajo -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-[#E89A7B] mr-2"></i>
                    Productos con Stock Bajo
                </h2>
                <a href="{{ route('productos.stock-bajo') }}" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($productosStockBajo->take(5) as $producto)
                <div class="flex items-center justify-between p-3 bg-[#FFF5F0] rounded-lg border border-[#E89A7B]">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $producto->categoria->nombre }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-[#E89A7B]">{{ $producto->stock_actual }}</p>
                        <p class="text-xs text-gray-600">Mín: {{ $producto->stock_minimo }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-[#5B8FCC] text-3xl mb-2"></i>
                    <p class="text-gray-600">Todos los productos tienen stock suficiente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos Administrador -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('ventas.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-cash-register text-4xl text-[#190C7B] mb-3"></i>
            <p class="font-semibold text-gray-800">Nueva Venta</p>
        </a>
        
        <a href="{{ route('productos.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-plus-circle text-4xl text-[#5B8FCC] mb-3"></i>
            <p class="font-semibold text-gray-800">Nuevo Producto</p>
        </a>
        
        <a href="{{ route('clientes.create') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-user-plus text-4xl text-[#8B7AB8] mb-3"></i>
            <p class="font-semibold text-gray-800">Nuevo Cliente</p>
        </a>
        
        <a href="{{ route('reportes.index') }}" 
           class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
            <i class="fas fa-chart-pie text-4xl text-[#E89A7B] mb-3"></i>
            <p class="font-semibold text-gray-800">Reportes</p>
        </a>
    </div>

@endif
@endsection