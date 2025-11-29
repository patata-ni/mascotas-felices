@extends('layouts.app')

@section('title', 'Reporte de Inventario')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('reportes.index') }}" class="hover:text-[#190C7B]">Reportes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Inventario</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Reporte de Inventario</h1>
</div>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-boxes text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $totalProductos }}</p>
        <p class="text-sm text-blue-100">Total Productos</p>
    </div>

    <div class="bg-[#5B8FCC] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-check-circle text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $productosEnStock }}</p>
        <p class="text-sm text-[#F3E8FF]">En Stock</p>
    </div>

    <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-exclamation-triangle text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $stockBajo }}</p>
        <p class="text-sm text-[#FFF5F0]">Stock Bajo</p>
    </div>

    <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <i class="fas fa-times-circle text-3xl opacity-75"></i>
        </div>
        <p class="text-3xl font-bold mb-1">{{ $sinStock }}</p>
        <p class="text-sm text-[#F3E8FF]">Sin Stock</p>
    </div>
</div>

<!-- Valorización del Inventario -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-dollar-sign text-[#5B8FCC] mr-2"></i>
        Valorización del Inventario
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#F5F3FF] rounded-lg p-4 border-l-4 border-[#5B8FCC]">
            <p class="text-sm text-[#190C7B] font-medium mb-2">Costo Total</p>
            <p class="text-2xl font-bold text-[#5B8FCC]">$ {{ number_format($costoTotal, 2) }}</p>
            <p class="text-xs text-[#5B8FCC] mt-1">Precio de compra total</p>
        </div>
        
        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
            <p class="text-sm text-[#190C7B] font-medium mb-2">Valor de Venta</p>
            <p class="text-2xl font-bold text-[#190C7B]">$ {{ number_format($valorVenta, 2) }}</p>
            <p class="text-xs text-[#5B8FCC] mt-1">Precio de venta total</p>
        </div>
        
        <div class="bg-[#F5F3FF] rounded-lg p-4 border-l-4 border-indigo-500">
            <p class="text-sm text-[#2D1B9E] font-medium mb-2">Ganancia Potencial</p>
            <p class="text-2xl font-bold text-[#2D1B9E]">$ {{ number_format($gananciaPotencial, 2) }}</p>
            <p class="text-xs text-[#190C7B] mt-1">
                Margen promedio: {{ number_format($margenPromedio, 1) }}%
            </p>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
            <select name="categoria" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Stock</label>
            <select name="estado" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="en_stock" {{ request('estado') == 'en_stock' ? 'selected' : '' }}>En Stock</option>
                <option value="bajo" {{ request('estado') == 'bajo' ? 'selected' : '' }}>Stock Bajo</option>
                <option value="sin_stock" {{ request('estado') == 'sin_stock' ? 'selected' : '' }}>Sin Stock</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
            <select name="orden" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                <option value="stock_asc" {{ request('orden') == 'stock_asc' ? 'selected' : '' }}>Stock (menor a mayor)</option>
                <option value="stock_desc" {{ request('orden') == 'stock_desc' ? 'selected' : '' }}>Stock (mayor a menor)</option>
                <option value="valor" {{ request('orden') == 'valor' ? 'selected' : '' }}>Valor en Inventario</option>
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

<!-- Tabla de Inventario -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-list text-gray-600 mr-2"></i>
            Detalle de Inventario
        </h3>
        <button onclick="window.print()" 
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-print mr-2"></i>Imprimir
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock Actual</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stock Mínimo</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio Compra</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio Venta</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor en Stock</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($productos as $producto)
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
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $producto->codigo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $producto->categoria->nombre }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-gray-800">{{ $producto->stock_actual }}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">
                            {{ $producto->stock_minimo }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-800">
                            $ {{ number_format($producto->precio_compra, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">
                            $ {{ number_format($producto->precio_venta, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-[#190C7B]">
                            $ {{ number_format($producto->precio_venta * $producto->stock_actual, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($producto->stock_actual == 0)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Sin Stock
                                </span>
                            @elseif($producto->stock_actual <= $producto->stock_minimo)
                                <span class="px-2 py-1 bg-[#FFF5F0] text-[#E89A7B] rounded text-xs font-medium">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Stock Bajo
                                </span>
                            @else
                                <span class="px-2 py-1 bg-[#EDE9FE] text-[#190C7B] rounded text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Normal
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay productos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $productos->links() }}
    </div>
</div>

<!-- Inventario por Categoría -->
<div class="bg-white rounded-lg shadow-lg p-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-chart-pie text-[#8B7AB8] mr-2"></i>
        Inventario por Categoría
    </h3>
    <canvas id="inventarioPorCategoria"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('inventarioPorCategoria').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($inventarioPorCategoria->pluck('nombre')),
        datasets: [{
            label: 'Cantidad de Productos',
            data: @json($inventarioPorCategoria->pluck('cantidad')),
            backgroundColor: 'rgba(25, 12, 123, 0.8)'
        }, {
            label: 'Valor en Inventario ($)',
            data: @json($inventarioPorCategoria->pluck('valor')),
            backgroundColor: 'rgba(91, 143, 204, 0.8)'
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
