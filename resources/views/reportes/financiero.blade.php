@extends('layouts.app')

@section('title', 'Reporte Financiero')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Financiero</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reporte Financiero</h1>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        <div class="flex items-end">
            <button type="submit" 
                    class="w-full px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Resumen Financiero -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-dollar-sign text-3xl opacity-75"></i>
            <i class="fas fa-arrow-up text-xl"></i>
        </div>
        <p class="text-sm text-[#F3E8FF] mb-1">Ingresos</p>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($totalIngresos, 2) }}</p>
        <p class="text-xs text-[#F3E8FF]">{{ $cantidadVentas }} ventas</p>
    </div>

    <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-dollar-sign text-3xl opacity-75"></i>
            <i class="fas fa-arrow-down text-xl"></i>
        </div>
        <p class="text-sm text-[#FFF5F0] mb-1">Costos</p>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($totalCostos, 2) }}</p>
        <p class="text-xs text-[#FFF5F0]">Costo de productos vendidos</p>
    </div>

    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-chart-line text-3xl opacity-75"></i>
        </div>
        <p class="text-sm text-[#F3E8FF] mb-1">Utilidad Bruta</p>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($utilidadBruta, 2) }}</p>
        <p class="text-xs text-[#F3E8FF]">
            Margen: {{ number_format($margenUtilidad, 1) }}%
        </p>
    </div>

    <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-shopping-cart text-3xl opacity-75"></i>
        </div>
        <p class="text-sm text-[#F3E8FF] mb-1">Inversión en Pedidos</p>
        <p class="text-3xl font-bold mb-1">$ {{ number_format($inversionPedidos, 2) }}</p>
        <p class="text-xs text-[#F3E8FF]">{{ $cantidadPedidos }} pedidos</p>
    </div>
</div>

<!-- Desglose de Ingresos -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-money-bill-wave text-[#5B8FCC] mr-2"></i>
        Desglose de Ingresos
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Por Método de Pago</h4>
            <div class="space-y-3">
                @foreach($ingresosPorMetodo as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div class="flex items-center">
                            @if($item->metodo_pago == 'efectivo')
                                <i class="fas fa-money-bill text-[#5B8FCC] mr-3"></i>
                            @elseif($item->metodo_pago == 'tarjeta')
                                <i class="fas fa-credit-card text-[#5B8FCC] mr-3"></i>
                            @elseif($item->metodo_pago == 'transferencia')
                                <i class="fas fa-exchange-alt text-[#8B7AB8] mr-3"></i>
                            @else
                                <i class="fas fa-mobile-alt text-[#E89A7B] mr-3"></i>
                            @endif
                            <span class="font-medium text-gray-800">{{ ucfirst(str_replace('_', '/', $item->metodo_pago)) }}</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">$ {{ number_format($item->total, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $item->cantidad }} ventas</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Componentes del Ingreso</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="text-gray-700">Subtotal (sin IGV)</span>
                    <span class="font-bold text-gray-800">$ {{ number_format($totalIngresos / 1.18, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="text-gray-700">IGV (18%)</span>
                    <span class="font-bold text-gray-800">$ {{ number_format($totalIngresos - ($totalIngresos / 1.18), 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-[#F5F3FF] rounded border-2 border-indigo-200">
                    <span class="font-semibold text-[#2D1B9E]">Total Ingresos</span>
                    <span class="font-bold text-[#2D1B9E] text-lg">$ {{ number_format($totalIngresos, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Análisis de Rentabilidad -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-chart-pie text-[#8B7AB8] mr-2"></i>
        Análisis de Rentabilidad
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="border-l-4 border-[#5B8FCC] pl-4">
            <p class="text-sm text-gray-600 mb-1">Ticket Promedio</p>
            <p class="text-2xl font-bold text-gray-800">$ {{ number_format($ticketPromedio, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Por venta</p>
        </div>
        
        <div class="border-l-4 border-blue-500 pl-4">
            <p class="text-sm text-gray-600 mb-1">Costo Promedio</p>
            <p class="text-2xl font-bold text-gray-800">$ {{ number_format($costoPromedio, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Por venta</p>
        </div>
        
        <div class="border-l-4 border-indigo-500 pl-4">
            <p class="text-sm text-gray-600 mb-1">Utilidad Promedio</p>
            <p class="text-2xl font-bold text-gray-800">$ {{ number_format($utilidadPromedio, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Por venta</p>
        </div>
    </div>

    <!-- Barra de Rentabilidad -->
    <div class="mt-6">
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Composición de Ingresos</span>
            <span class="font-semibold text-gray-800">100%</span>
        </div>
        <div class="w-full h-10 bg-gray-200 rounded-lg overflow-hidden flex">
            <div class="bg-[#E89A7B] flex items-center justify-center text-white text-xs font-bold" 
                 style="width: {{ ($totalCostos / $totalIngresos) * 100 }}%">
                Costos {{ number_format(($totalCostos / $totalIngresos) * 100, 1) }}%
            </div>
            <div class="bg-[#8B7AB8] flex items-center justify-center text-white text-xs font-bold" 
                 style="width: {{ ($utilidadBruta / $totalIngresos) * 100 }}%">
                Utilidad {{ number_format(($utilidadBruta / $totalIngresos) * 100, 1) }}%
            </div>
        </div>
    </div>
</div>

<!-- Productos Más Rentables -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-star text-[#E89A7B] mr-2"></i>
        Top 10 Productos Más Rentables
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Vendidos</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ingresos</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Costos</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Utilidad</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Margen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($productosRentables as $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}"
                                         class="w-10 h-10 rounded object-cover mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="font-semibold text-gray-800 text-sm">{{ $producto->nombre }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 bg-gray-100 rounded font-semibold text-sm">
                                {{ $producto->total_vendido }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">
                            $ {{ number_format($producto->total_ingresos, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-600">
                            $ {{ number_format($producto->total_costos, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-[#5B8FCC]">
                            $ {{ number_format($producto->utilidad, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 {{ $producto->margen >= 30 ? 'bg-[#EDE9FE] text-[#190C7B]' : ($producto->margen >= 15 ? 'bg-[#FFF5F0] text-[#E89A7B]' : 'bg-red-100 text-red-800') }} rounded text-xs font-bold">
                                {{ number_format($producto->margen, 1) }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Evolución Mensual -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-[#190C7B] mr-2"></i>
            Evolución Financiera Mensual
        </h3>
        <canvas id="evolucionMensual"></canvas>
    </div>

    <!-- Distribución de Utilidad -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-[#5B8FCC] mr-2"></i>
            Composición Financiera
        </h3>
        <canvas id="composicionFinanciera"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Evolución Mensual
const ctxEv = document.getElementById('evolucionMensual').getContext('2d');
new Chart(ctxEv, {
    type: 'line',
    data: {
        labels: @json($evolucionMensual->pluck('mes')),
        datasets: [{
            label: 'Ingresos',
            data: @json($evolucionMensual->pluck('ingresos')),
            borderColor: 'rgba(25, 12, 123, 1)',
            backgroundColor: 'rgba(25, 12, 123, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Costos',
            data: @json($evolucionMensual->pluck('costos')),
            borderColor: 'rgba(232, 154, 123, 1)',
            backgroundColor: 'rgba(232, 154, 123, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Utilidad',
            data: @json($evolucionMensual->pluck('utilidad')),
            borderColor: 'rgba(91, 143, 204, 1)',
            backgroundColor: 'rgba(91, 143, 204, 0.1)',
            fill: true,
            tension: 0.4
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
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$ ' + value.toFixed(0);
                    }
                }
            }
        }
    }
});

// Composición Financiera
const ctxComp = document.getElementById('composicionFinanciera').getContext('2d');
new Chart(ctxComp, {
    type: 'doughnut',
    data: {
        labels: ['Costos', 'Utilidad Bruta'],
        datasets: [{
            data: [{{ $totalCostos }}, {{ $utilidadBruta }}],
            backgroundColor: [
                'rgba(232, 154, 123, 0.8)',
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
</script>
@endsection
