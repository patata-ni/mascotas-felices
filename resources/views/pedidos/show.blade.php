@extends('layouts.app')

@section('title', 'Detalle de Pedido')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('pedidos.index') }}" class="hover:text-[#190C7B]">Pedidos</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">{{ $pedido->numero_pedido }}</span>
    </div>
    
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Pedido {{ $pedido->numero_pedido }}</h1>
        
        <div class="flex space-x-3">
            @if($pedido->estado == 'pendiente' && (Auth::user()->esAdministrador() || Auth::user()->esInventario()))
                <a href="{{ route('pedidos.edit', $pedido) }}" 
                   class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('pedidos.recibir', $pedido) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Marcar como recibido? Se actualizará el stock de los productos.')"
                      class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Marcar Recibido
                    </button>
                </form>
                <form action="{{ route('pedidos.cancelar', $pedido) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Cancelar este pedido?')"
                      class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-ban mr-2"></i>Cancelar
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Información del Pedido -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Información del Proveedor -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-truck text-[#190C7B] mr-2"></i>
                Información del Proveedor
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Empresa</p>
                    <p class="font-semibold text-gray-800">{{ $pedido->proveedor->empresa }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">RFC</p>
                    <p class="font-semibold text-gray-800">{{ $pedido->proveedor->ruc }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Contacto</p>
                    <p class="font-semibold text-gray-800">{{ $pedido->proveedor->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Teléfono</p>
                    <p class="font-semibold text-gray-800">{{ $pedido->proveedor->telefono }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold text-gray-800">{{ $pedido->proveedor->email }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('proveedores.show', $pedido->proveedor) }}" 
                   class="text-[#190C7B] hover:text-[#2D1B9E] text-sm font-medium">
                    Ver perfil completo del proveedor <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Detalles de Productos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-box text-[#5B8FCC] mr-2"></i>
                Productos del Pedido
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pedido->detalles as $detalle)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        @if($detalle->producto->imagen)
                                            <img src="{{ asset('storage/' . $detalle->producto->imagen) }}" 
                                                 alt="{{ $detalle->producto->nombre }}"
                                                 class="w-12 h-12 rounded object-cover mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $detalle->producto->nombre }}</p>
                                            <p class="text-xs text-gray-500">{{ $detalle->producto->codigo }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 bg-gray-100 rounded font-semibold">{{ $detalle->cantidad }}</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="font-semibold text-gray-800">$ {{ number_format($detalle->precio_unitario, 2) }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="font-bold text-[#190C7B]">$ {{ number_format($detalle->subtotal, 2) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr class="border-t-2">
                            <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800 text-lg">Total:</td>
                            <td class="px-4 py-3 text-right font-bold text-[#190C7B] text-xl">$ {{ number_format($pedido->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Observaciones -->
        @if($pedido->observaciones)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    <i class="fas fa-comment text-gray-600 mr-2"></i>
                    Observaciones
                </h3>
                <p class="text-gray-700">{{ $pedido->observaciones }}</p>
            </div>
        @endif
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Estado del Pedido -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Estado</h3>
            
            <div class="text-center mb-4">
                @if($pedido->estado == 'pendiente')
                    <div class="w-16 h-16 bg-[#FFF5F0] rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-[#E89A7B] text-3xl"></i>
                    </div>
                    <p class="font-bold text-[#E89A7B] text-lg">Pendiente</p>
                @elseif($pedido->estado == 'recibido')
                    <div class="w-16 h-16 bg-[#EDE9FE] rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-[#5B8FCC] text-3xl"></i>
                    </div>
                    <p class="font-bold text-[#5B8FCC] text-lg">Recibido</p>
                @else
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                    </div>
                    <p class="font-bold text-red-600 text-lg">Cancelado</p>
                @endif
            </div>

            <div class="space-y-2 text-sm border-t pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">N° Pedido:</span>
                    <span class="font-semibold">{{ $pedido->numero_pedido }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha Pedido:</span>
                    <span class="font-semibold">{{ $pedido->created_at->format('d/m/Y') }}</span>
                </div>
                @if($pedido->fecha_esperada)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha Esperada:</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($pedido->fecha_esperada)->format('d/m/Y') }}</span>
                    </div>
                @endif
                @if($pedido->fecha_recepcion)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha Recepción:</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($pedido->fecha_recepcion)->format('d/m/Y') }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Responsable:</span>
                    <span class="font-semibold">{{ $pedido->usuario->name }}</span>
                </div>
            </div>
        </div>

        <!-- Resumen -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Productos:</span>
                    <span class="font-semibold">{{ $pedido->detalles->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Unidades:</span>
                    <span class="font-semibold">{{ $pedido->detalles->sum('cantidad') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t">
                    <span class="font-bold text-gray-800">Total:</span>
                    <span class="font-bold text-[#190C7B] text-lg">$ {{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-gray-600 mr-2"></i>
                Historial
            </h3>
            
            <div class="space-y-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-[#5B8FCC] text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-gray-800">Pedido Creado</p>
                        <p class="text-xs text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($pedido->estado == 'recibido' && $pedido->fecha_recepcion)
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-[#EDE9FE] rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-[#5B8FCC] text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">Pedido Recibido</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pedido->fecha_recepcion)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endif

                @if($pedido->estado == 'cancelado')
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-red-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">Pedido Cancelado</p>
                            <p class="text-xs text-gray-500">{{ $pedido->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
