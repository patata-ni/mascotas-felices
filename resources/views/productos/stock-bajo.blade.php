@extends('layouts.app')

@section('title', 'Productos con Stock Bajo')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('productos.index') }}" class="hover:text-[#190C7B]">Productos</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Stock Bajo</span>
    </div>
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Productos con Stock Bajo</h1>
            <p class="text-gray-600 mt-2">Productos que están en stock mínimo o por debajo</p>
        </div>
        <a href="{{ route('productos.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Productos
        </a>
    </div>
</div>

<!-- Alerta de Stock Crítico -->
@if($productos->count() > 0)
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
            <div>
                <p class="font-bold">¡Atención! Hay {{ $productos->count() }} productos con stock bajo</p>
                <p class="text-sm">Es necesario realizar pedidos a proveedores para reponer el inventario.</p>
            </div>
        </div>
    </div>
@else
    <div class="bg-[#EDE9FE] border-l-4 border-[#5B8FCC] text-green-700 p-4 rounded mb-6" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-2xl mr-3"></i>
            <div>
                <p class="font-bold">¡Todo en orden!</p>
                <p class="text-sm">No hay productos con stock bajo en este momento.</p>
            </div>
        </div>
    </div>
@endif

<!-- Listado de Productos -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Mínimo</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Faltante</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($productos as $producto)
                    <tr class="hover:bg-gray-50 {{ $producto->stock_actual == 0 ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}"
                                         class="w-12 h-12 rounded object-cover mr-4">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mr-4">
                                        <i class="fas fa-box text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $producto->nombre }}</p>
                                    <p class="text-xs text-gray-500">Código: {{ $producto->codigo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-[#EDE9FE] text-[#190C7B] rounded">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold {{ $producto->stock_actual == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $producto->stock_actual }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-gray-600">{{ $producto->stock_minimo }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-red-600">
                                {{ max(0, $producto->stock_minimo - $producto->stock_actual) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($producto->stock_actual == 0)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Sin Stock
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#FFF5F0] text-[#E89A7B]">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Stock Bajo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('productos.show', $producto) }}" 
                               class="text-[#190C7B] hover:text-[#2D1B9E] mr-3" 
                               title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('productos.edit', $producto) }}" 
                               class="text-[#E89A7B] hover:text-[#D68A6B] mr-3" 
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('pedidos.create', ['producto_id' => $producto->id]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-[#5B8FCC] text-white rounded hover:bg-[#190C7B] transition" 
                               title="Hacer pedido de este producto">
                                <i class="fas fa-shopping-cart mr-1"></i>
                                Pedir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-check-circle text-6xl mb-4 text-[#5B8FCC]"></i>
                                <p class="text-lg font-semibold text-gray-600">¡Todo en orden!</p>
                                <p class="text-sm">No hay productos con stock bajo</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recomendaciones -->
@if($productos->count() > 0)
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-[#5B8FCC] text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-[#190C7B]">Recomendaciones</h3>
                <div class="mt-2 text-sm text-[#190C7B]">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Crea pedidos a proveedores para reponer el stock</li>
                        <li>Verifica la demanda de estos productos antes de ordenar</li>
                        <li>Considera ajustar los stock mínimos si es necesario</li>
                    </ul>
                </div>
                <div class="mt-4">
                    <a href="{{ route('pedidos.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Crear Pedido a Proveedor
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
