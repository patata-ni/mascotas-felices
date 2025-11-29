@extends('layouts.app')

@section('title', 'Reporte de Pedidos')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Pedidos</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reporte de Pedidos</h1>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-clipboard-list text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $totalPedidos }}</p>
        <p class="text-sm text-blue-100">Total Pedidos</p>
    </div>

    <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-clock text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $pedidosPendientes }}</p>
        <p class="text-sm text-[#FFF5F0]">Pendientes</p>
    </div>

    <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-check-circle text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $pedidosRecibidos }}</p>
        <p class="text-sm text-[#F3E8FF]">Recibidos</p>
    </div>

    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-dollar-sign text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($montoTotal, 2) }}</p>
        <p class="text-sm text-[#F3E8FF]">Monto Total</p>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
            <select name="proveedor" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->empresa }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="estado" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="recibido" {{ request('estado') == 'recibido' ? 'selected' : '' }}>Recibido</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
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

<!-- Tabla de Pedidos -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-list text-gray-600 mr-2"></i>
        Listado de Pedidos
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Pedido</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pedidos as $pedido)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-sm text-gray-800">
                            {{ $pedido->numero_pedido }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $pedido->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <p class="font-semibold text-gray-800">{{ $pedido->proveedor->empresa }}</p>
                            <p class="text-xs text-gray-500">{{ $pedido->proveedor->nombre }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full font-semibold text-sm">
                                {{ $pedido->detalles->count() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800">
                            $ {{ number_format($pedido->total, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($pedido->estado == 'pendiente')
                                <span class="px-3 py-1 bg-[#FFF5F0] text-[#E89A7B] rounded-full text-xs font-medium">
                                    <i class="fas fa-clock mr-1"></i>Pendiente
                                </span>
                            @elseif($pedido->estado == 'recibido')
                                <span class="px-3 py-1 bg-[#EDE9FE] text-[#190C7B] rounded-full text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Recibido
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Cancelado
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pedidos.show', $pedido) }}" 
                               class="text-[#190C7B] hover:text-[#2D1B9E]" 
                               title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay pedidos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>
</div>

<!-- Pedidos por Proveedor -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-truck text-[#5B8FCC] mr-2"></i>
        Pedidos por Proveedor
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($pedidosPorProveedor as $item)
            <div class="border rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-bold text-gray-800">{{ $item->proveedor->empresa }}</p>
                        <p class="text-xs text-gray-500">{{ $item->proveedor->ruc }}</p>
                    </div>
                    <span class="px-2 py-1 bg-[#EDE9FE] text-[#190C7B] rounded text-xs font-bold">
                        {{ $item->total_pedidos }}
                    </span>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pedidos:</span>
                        <span class="font-semibold">$ {{ number_format($item->monto_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Pendientes:</span>
                        <span>{{ $item->pendientes }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Pedidos por Estado -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-[#190C7B] mr-2"></i>
            Pedidos por Estado
        </h3>
        <canvas id="pedidosEstado"></canvas>
    </div>

    <!-- Pedidos por Mes -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-[#5B8FCC] mr-2"></i>
            Pedidos por Mes
        </h3>
        <canvas id="pedidosMes"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Pedidos por Estado
const ctxEstado = document.getElementById('pedidosEstado').getContext('2d');
new Chart(ctxEstado, {
    type: 'doughnut',
    data: {
        labels: ['Pendientes', 'Recibidos', 'Cancelados'],
        datasets: [{
            data: [{{ $pedidosPendientes }}, {{ $pedidosRecibidos }}, {{ $pedidosCancelados ?? 0 }}],
            backgroundColor: [
                'rgba(232, 154, 123, 0.8)',
                'rgba(91, 143, 204, 0.8)',
                'rgba(139, 122, 184, 0.8)'
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

// Pedidos por Mes
const ctxMes = document.getElementById('pedidosMes').getContext('2d');
new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: @json($pedidosPorMes->pluck('mes')),
        datasets: [{
            label: 'Número de Pedidos',
            data: @json($pedidosPorMes->pluck('total')),
            backgroundColor: 'rgba(25, 12, 123, 0.8)'
        }, {
            label: 'Monto Total ($)',
            data: @json($pedidosPorMes->pluck('monto')),
            backgroundColor: 'rgba(139, 122, 184, 0.8)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
