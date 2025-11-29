@extends('layouts.app')

@section('title', 'Pedidos a Proveedores')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pedidos a Proveedores</h1>
            <p class="text-gray-600 mt-1">Gestión de pedidos y reabastecimiento de inventario</p>
        </div>
        @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <a href="{{ route('pedidos.create') }}" 
               class="px-4 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Pedido
            </a>
        @endif
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('pedidos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
            <select name="proveedor_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->empresa }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="recibido" {{ request('estado') == 'recibido' ? 'selected' : '' }}>Recibido</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
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
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            <a href="{{ route('pedidos.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Pedidos</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pedidos->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-[#190C7B] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Pendientes</p>
                <p class="text-3xl font-bold text-[#E89A7B]">{{ $pedidos->where('estado', 'pendiente')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-[#FFF5F0] rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-[#E89A7B] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Recibidos</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">{{ $pedidos->where('estado', 'recibido')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-[#5B8FCC] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Monto Total</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">$ {{ number_format($pedidos->sum('total'), 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pedidos -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedido</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsable</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pedidos as $pedido)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $pedido->numero_pedido }}</p>
                            <p class="text-xs text-gray-500">ID: #{{ $pedido->id }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $pedido->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $pedido->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $pedido->proveedor->empresa }}</p>
                            <p class="text-xs text-gray-500">{{ $pedido->proveedor->nombre }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $pedido->usuario->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-[#190C7B] rounded-full text-sm font-medium">
                                {{ $pedido->detalles->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-lg font-bold text-gray-800">$ {{ number_format($pedido->total, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
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
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('pedidos.show', $pedido) }}" 
                                   class="text-[#5B8FCC] hover:text-[#190C7B]" 
                                   title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($pedido->estado == 'pendiente' && (Auth::user()->esAdministrador() || Auth::user()->esInventario()))
                                    <a href="{{ route('pedidos.edit', $pedido) }}" 
                                       class="text-[#190C7B] hover:text-[#2D1B9E]" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pedidos.recibir', $pedido) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Marcar como recibido? Se actualizará el stock.')"
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-[#5B8FCC] hover:text-[#2D1B9E]" 
                                                title="Marcar como recibido">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('pedidos.cancelar', $pedido) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Cancelar este pedido?')"
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800" 
                                                title="Cancelar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                            <p>No hay pedidos registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pedidos->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection
