@extends('layouts.app')

@section('title', 'Productos Más Vendidos')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Productos Más Vendidos</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Productos Más Vendidos</h1>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
            <select name="categoria" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todas</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
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

<!-- Top 3 Productos -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @foreach($topProductos->take(3) as $index => $producto)
        <div class="{{ $index == 0 ? 'bg-[#E89A7B]' : ($index == 1 ? 'bg-[#5B8FCC]' : 'bg-[#8B7AB8]') }} rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-3xl font-bold">#{{ $index + 1 }}</span>
                </div>
                <i class="fas fa-trophy text-4xl opacity-75"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ $producto->nombre }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="opacity-90">Unidades:</span>
                    <span class="font-bold">{{ $producto->total_vendido }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="opacity-90">Ingresos:</span>
                    <span class="font-bold">$ {{ number_format($producto->total_ingresos, 2) }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Lista Completa -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-list text-[#8B7AB8] mr-2"></i>
        Ranking Completo
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pos.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Unidades Vendidas</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Ingresos</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio Promedio</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">% del Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($topProductos as $index => $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">
                            @if($index < 3)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                    {{ $index == 0 ? 'bg-[#FFF5F0] text-[#E89A7B]' : ($index == 1 ? 'bg-gray-100 text-gray-800' : 'bg-[#FFF5F0] text-[#E89A7B]') }} 
                                    font-bold">
                                    {{ $index + 1 }}
                                </span>
                            @else
                                <span class="text-gray-600 font-semibold">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}"
                                         class="w-12 h-12 rounded object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $producto->codigo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $producto->categoria->nombre }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 bg-[#EDE9FE] text-[#190C7B] rounded-full font-bold">
                                {{ $producto->total_vendido }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-[#5B8FCC]">
                            $ {{ number_format($producto->total_ingresos, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-800">
                            $ {{ number_format($producto->precio_promedio, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-[#190C7B] h-2 rounded-full" 
                                         style="width: {{ $producto->porcentaje }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">{{ number_format($producto->porcentaje, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay productos vendidos en este período</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    
    <!-- Top 10 por Unidades -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-[#5B8FCC] mr-2"></i>
            Top 10 por Unidades
        </h3>
        <canvas id="topUnidades"></canvas>
    </div>

    <!-- Top 10 por Ingresos -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-[#5B8FCC] mr-2"></i>
            Top 10 por Ingresos
        </h3>
        <canvas id="topIngresos"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Top 10 por Unidades
const ctxUnidades = document.getElementById('topUnidades').getContext('2d');
new Chart(ctxUnidades, {
    type: 'bar',
    data: {
        labels: @json($topProductos->take(10)->pluck('nombre')),
        datasets: [{
            label: 'Unidades Vendidas',
            data: @json($topProductos->take(10)->pluck('total_vendido')),
            backgroundColor: 'rgba(91, 143, 204, 0.8)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { beginAtZero: true }
        }
    }
});

// Top 10 por Ingresos
const ctxIngresos = document.getElementById('topIngresos').getContext('2d');
new Chart(ctxIngresos, {
    type: 'bar',
    data: {
        labels: @json($topProductos->take(10)->pluck('nombre')),
        datasets: [{
            label: 'Ingresos ($)',
            data: @json($topProductos->take(10)->pluck('total_ingresos')),
            backgroundColor: 'rgba(139, 122, 184, 0.8)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { 
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
</script>
@endsection
