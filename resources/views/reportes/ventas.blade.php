@extends('layouts.app')

@section('title', 'Reporte de Ventas')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Ventas</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reporte de Ventas</h1>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
            <input type="date" 
                   name="fecha_desde" 
                   value="{{ request('fecha_desde', now()->startOfMonth()->format('Y-m-d')) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
            <input type="date" 
                   name="fecha_hasta" 
                   value="{{ request('fecha_hasta', now()->format('Y-m-d')) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
            <select name="metodo_pago" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                <option value="yape_plin" {{ request('metodo_pago') == 'yape_plin' ? 'selected' : '' }}>Yape/Plin</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" 
                    class="w-full px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-dollar-sign text-3xl opacity-75"></i>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Total</span>
        </div>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($totalVentas, 2) }}</p>
        <p class="text-sm text-[#F3E8FF]">Total en Ventas</p>
    </div>

    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-receipt text-3xl opacity-75"></i>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Cantidad</span>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $cantidadVentas }}</p>
        <p class="text-sm text-blue-100">Ventas Realizadas</p>
    </div>

    <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-chart-line text-3xl opacity-75"></i>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Promedio</span>
        </div>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($promedioVenta, 2) }}</p>
        <p class="text-sm text-[#F3E8FF]">Ticket Promedio</p>
    </div>

    <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-box text-3xl opacity-75"></i>
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Total</span>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $productosVendidos }}</p>
        <p class="text-sm text-[#FFF5F0]">Productos Vendidos</p>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- Ventas por Día -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-calendar text-[#190C7B] mr-2"></i>
            Ventas Diarias
        </h3>
        <canvas id="ventasDiarias"></canvas>
    </div>

    <!-- Ventas por Método de Pago -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-credit-card text-[#5B8FCC] mr-2"></i>
            Ventas por Método de Pago
        </h3>
        <canvas id="metodoPago"></canvas>
    </div>
</div>

<!-- Tabla de Ventas -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-list text-gray-600 mr-2"></i>
        Detalle de Ventas
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método Pago</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($ventas as $venta)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $venta->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($venta->cliente)
                                {{ $venta->cliente->nombre }}
                            @else
                                <span class="text-gray-400">Cliente no registrado</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $venta->detalles->count() }} productos
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($venta->metodo_pago == 'efectivo')
                                <span class="px-2 py-1 bg-[#EDE9FE] text-[#190C7B] rounded text-xs font-medium">
                                    <i class="fas fa-money-bill mr-1"></i>Efectivo
                                </span>
                            @elseif($venta->metodo_pago == 'tarjeta')
                                <span class="px-2 py-1 bg-blue-100 text-[#190C7B] rounded text-xs font-medium">
                                    <i class="fas fa-credit-card mr-1"></i>Tarjeta
                                </span>
                            @elseif($venta->metodo_pago == 'transferencia')
                                <span class="px-2 py-1 bg-[#F3E8FF] text-[#8B7AB8] rounded text-xs font-medium">
                                    <i class="fas fa-exchange-alt mr-1"></i>Transferencia
                                </span>
                            @else
                                <span class="px-2 py-1 bg-[#FFF5F0] text-[#E89A7B] rounded text-xs font-medium">
                                    <i class="fas fa-mobile-alt mr-1"></i>Yape/Plin
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-right text-gray-800">
                            $ {{ number_format($venta->total, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <a href="{{ route('ventas.show', $venta) }}" 
                               class="text-[#190C7B] hover:text-[#2D1B9E] mr-2" 
                               title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay ventas en este período</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ventas->links() }}
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Ventas Diarias
const ctxDiarias = document.getElementById('ventasDiarias').getContext('2d');
new Chart(ctxDiarias, {
    type: 'line',
    data: {
        labels: @json($ventasPorDia->pluck('fecha')),
        datasets: [{
            label: 'Ventas Diarias',
            data: @json($ventasPorDia->pluck('total')),
            backgroundColor: 'rgba(25, 12, 123, 0.1)',
            borderColor: 'rgba(25, 12, 123, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$ ' + value.toFixed(2);
                    }
                }
            }
        }
    }
});

// Gráfico de Método de Pago
const ctxMetodo = document.getElementById('metodoPago').getContext('2d');
new Chart(ctxMetodo, {
    type: 'doughnut',
    data: {
        labels: @json($ventasPorMetodo->pluck('metodo')),
        datasets: [{
            data: @json($ventasPorMetodo->pluck('total')),
            backgroundColor: [
                'rgba(25, 12, 123, 0.8)',
                'rgba(91, 143, 204, 0.8)',
                'rgba(139, 122, 184, 0.8)',
                'rgba(232, 154, 123, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection
