@extends('layouts.app')

@section('title', 'Reporte de Clientes')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Clientes</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reporte de Clientes</h1>
</div>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-users text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $totalClientes }}</p>
        <p class="text-sm text-blue-100">Total Clientes</p>
    </div>

    <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-shopping-cart text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $clientesActivos }}</p>
        <p class="text-sm text-[#F3E8FF]">Clientes Activos</p>
    </div>

    <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-user-plus text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $clientesNuevos }}</p>
        <p class="text-sm text-[#F3E8FF]">Nuevos este Mes</p>
    </div>

    <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-star text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ number_format($puntosTotal) }}</p>
        <p class="text-sm text-[#FFF5F0]">Puntos Totales</p>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Cliente</label>
            <input type="text" 
                   name="buscar" 
                   value="{{ request('buscar') }}"
                   placeholder="Nombre, DNI, email..."
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
            <select name="orden" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                <option value="compras" {{ request('orden') == 'compras' ? 'selected' : '' }}>Más Compras</option>
                <option value="monto" {{ request('orden') == 'monto' ? 'selected' : '' }}>Mayor Gasto</option>
                <option value="puntos" {{ request('orden') == 'puntos' ? 'selected' : '' }}>Más Puntos</option>
                <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más Recientes</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
            <input type="date" 
                   name="fecha_desde" 
                   value="{{ request('fecha_desde', now()->startOfMonth()->format('Y-m-d')) }}"
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

<!-- Top 5 Clientes -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-trophy text-[#E89A7B] mr-2"></i>
        Top 5 Clientes del Mes
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach($topClientes->take(5) as $index => $cliente)
            <div class="{{ $index == 0 ? 'bg-[#FFF5F0]' : 'bg-[#F5F3FF]' }} rounded-lg p-4 border-2 {{ $index == 0 ? 'border-[#E89A7B]' : 'border-[#DDD6FE]' }}">
                <div class="text-center mb-3">
                    <div class="w-16 h-16 mx-auto mb-2 {{ $index == 0 ? 'bg-[#E89A7B]' : 'bg-[#8B7AB8]' }} rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">#{{ $index + 1 }}</span>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                </div>
                <div class="space-y-1 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <span>Compras:</span>
                        <span class="font-bold">{{ $cliente->total_compras }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total:</span>
                        <span class="font-bold">$ {{ number_format($cliente->total_gastado, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Puntos:</span>
                        <span class="font-bold text-[#E89A7B]">{{ $cliente->puntos_fidelidad }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Tabla de Clientes -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-list text-gray-600 mr-2"></i>
        Listado de Clientes
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Compras</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Gastado</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Puntos</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Última Compra</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($clientes as $cliente)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                                <p class="text-xs text-gray-500">DNI: {{ $cliente->dni }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <div>
                                <p><i class="fas fa-envelope mr-1 text-gray-400"></i>{{ $cliente->email }}</p>
                                <p><i class="fas fa-phone mr-1 text-gray-400"></i>{{ $cliente->telefono }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 bg-[#EDE9FE] text-[#190C7B] rounded-full font-bold text-sm">
                                {{ $cliente->ventas_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-[#5B8FCC]">
                            $ {{ number_format($cliente->ventas_sum_total ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 bg-[#FFF5F0] text-[#E89A7B] rounded-full font-bold text-sm">
                                <i class="fas fa-star mr-1"></i>{{ $cliente->puntos_fidelidad }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">
                            @if($cliente->ventas_max_created_at)
                                {{ \Carbon\Carbon::parse($cliente->ventas_max_created_at)->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('clientes.show', $cliente) }}" 
                               class="text-[#190C7B] hover:text-[#2D1B9E] mr-2" 
                               title="Ver perfil">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clientes.historial', $cliente) }}" 
                               class="text-[#5B8FCC] hover:text-[#2D1B9E]" 
                               title="Ver historial">
                                <i class="fas fa-history"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay clientes registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clientes->links() }}
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    
    <!-- Clientes Nuevos por Mes -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-[#5B8FCC] mr-2"></i>
            Nuevos Clientes por Mes
        </h3>
        <canvas id="clientesPorMes"></canvas>
    </div>

    <!-- Distribución por Compras -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-[#8B7AB8] mr-2"></i>
            Distribución por Nivel de Compras
        </h3>
        <canvas id="distribucionCompras"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Clientes por Mes
const ctxMes = document.getElementById('clientesPorMes').getContext('2d');
new Chart(ctxMes, {
    type: 'line',
    data: {
        labels: @json($clientesPorMes->pluck('mes')),
        datasets: [{
            label: 'Nuevos Clientes',
            data: @json($clientesPorMes->pluck('total')),
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
            y: { beginAtZero: true }
        }
    }
});

// Distribución por Compras
const ctxDistribucion = document.getElementById('distribucionCompras').getContext('2d');
new Chart(ctxDistribucion, {
    type: 'doughnut',
    data: {
        labels: ['Sin Compras', '1-3 Compras', '4-10 Compras', 'Más de 10 Compras'],
        datasets: [{
            data: @json($distribucionCompras),
            backgroundColor: [
                'rgba(139, 122, 184, 0.8)',
                'rgba(232, 154, 123, 0.8)',
                'rgba(91, 143, 204, 0.8)',
                'rgba(25, 12, 123, 0.8)'
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
