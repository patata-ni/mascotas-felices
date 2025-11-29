@extends('layouts.app')

@section('title', 'Historial de Compras')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('clientes.index') }}" class="hover:text-[#190C7B]">Clientes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <a href="{{ route('clientes.show', $cliente) }}" class="hover:text-[#190C7B]">{{ $cliente->nombre }}</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Historial</span>
    </div>
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Historial de Compras</h1>
            <p class="text-gray-600 mt-1">{{ $cliente->nombre }} - {{ $cliente->tipo_documento }}: {{ $cliente->documento }}</p>
        </div>
        
        <a href="{{ route('clientes.show', $cliente) }}" 
           class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>
</div>

<!-- Resumen -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Compras</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalCompras }}</p>
            </div>
            <div class="w-12 h-12 bg-[#F3E8FF] rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-[#8B7AB8] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Monto Total</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">$ {{ number_format($montoTotal, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-[#5B8FCC] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Puntos Acumulados</p>
                <p class="text-3xl font-bold text-[#E89A7B]">{{ $cliente->puntos_fidelidad }}</p>
            </div>
            <div class="w-12 h-12 bg-[#FFF5F0] rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-[#E89A7B] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Ticket Promedio</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">
                    $ {{ $totalCompras > 0 ? number_format($montoTotal / $totalCompras, 2) : '0.00' }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-[#5B8FCC] text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('clientes.historial', $cliente) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
            <input type="date" 
                   name="fecha_desde" 
                   value="{{ request('fecha_desde') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
            <input type="date" 
                   name="fecha_hasta" 
                   value="{{ request('fecha_hasta') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
            <select name="metodo_pago" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="yape" {{ request('metodo_pago') == 'yape' ? 'selected' : '' }}>Yape</option>
                <option value="plin" {{ request('metodo_pago') == 'plin' ? 'selected' : '' }}>Plin</option>
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            <a href="{{ route('clientes.historial', $cliente) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Tabla de Historial -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Venta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método Pago</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($historial as $venta)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">#{{ $venta->id }}</p>
                            <p class="text-xs text-gray-500">{{ $venta->usuario->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $venta->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $venta->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $venta->detalles->count() }} producto(s)</p>
                            <p class="text-xs text-gray-500">{{ $venta->detalles->sum('cantidad') }} unidades</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-[#190C7B] rounded text-xs font-medium">
                                {{ ucfirst($venta->metodo_pago) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-lg font-bold text-[#5B8FCC]">$ {{ number_format($venta->total, 2) }}</p>
                            <p class="text-xs text-gray-500">IGV: $ {{ number_format($venta->igv, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($venta->estado == 'completada')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Completada
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-ban mr-1"></i>Anulada
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('ventas.show', $venta) }}" 
                                   class="text-[#5B8FCC] hover:text-[#190C7B]" 
                                   title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('ventas.imprimir', $venta) }}" 
                                   class="text-[#8B7AB8] hover:text-[#8B7AB8]" 
                                   title="Imprimir"
                                   target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                            <p>No hay compras registradas</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($historial->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $historial->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
