@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('ventas.ind                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">$ {{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t">
                    <span class="font-bold text-gray-800">Total:</span>
                    <span class="font-bold text-[#5B8FCC] text-lg">$ {{ number_format($venta->total, 2) }}</span>
                </div>ss="hover:text-[#5B8FCC]">Ventas</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Venta #{{ $venta->id }}</span>
    </div>
    
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Detalle de Venta #{{ $venta->id }}</h1>
        
        <div class="flex space-x-3">
            <a href="{{ route('ventas.imprimir', $venta) }}" 
               target="_blank"
               class="px-4 py-2 bg-[#8B7AB8] text-white rounded-lg hover:bg-[#9B8AC4] transition">
                <i class="fas fa-print mr-2"></i>Imprimir
            </a>
            @if($venta->estado == 'completada' && Auth::user()->esAdministrador())
                <form action="{{ route('ventas.anular', $venta) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Anular esta venta? Se revertirá el stock y los puntos del cliente.')"
                      class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-ban mr-2"></i>Anular Venta
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Información de la Venta -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Detalles de Productos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-box text-[#5B8FCC] mr-2"></i>
                Productos
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
                        @foreach($venta->detalles as $detalle)
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
                                    <p class="font-bold text-[#5B8FCC]">$ {{ number_format($detalle->subtotal, 2) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal:</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">$ {{ number_format($venta->subtotal, 2) }}</td>
                        </tr>
                        <tr class="border-t-2">
                            <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800 text-lg">Total:</td>
                            <td class="px-4 py-3 text-right font-bold text-[#5B8FCC] text-xl">$ {{ number_format($venta->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Información del Cliente -->
        @if($venta->cliente)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user text-[#190C7B] mr-2"></i>
                    Información del Cliente
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nombre Completo</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">DNI</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->dni }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Teléfono</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->telefono }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Puntos Ganados</p>
                        <p class="font-semibold text-[#E89A7B] flex items-center">
                            <i class="fas fa-star mr-2"></i>{{ $venta->puntos_ganados }} puntos
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('clientes.show', $venta->cliente) }}" 
                       class="text-[#190C7B] hover:text-[#2D1B9E] text-sm font-medium">
                        Ver perfil completo del cliente <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Estado de la Venta -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Estado</h3>
            
            <div class="text-center mb-4">
                @if($venta->estado == 'completada')
                    <div class="w-16 h-16 bg-[#EDE9FE] rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-[#5B8FCC] text-3xl"></i>
                    </div>
                    <p class="font-bold text-[#5B8FCC] text-lg">Completada</p>
                @else
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-ban text-red-600 text-3xl"></i>
                    </div>
                    <p class="font-bold text-red-600 text-lg">Anulada</p>
                @endif
            </div>

            <div class="space-y-2 text-sm border-t pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha:</span>
                    <span class="font-semibold">{{ $venta->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Vendedor:</span>
                    <span class="font-semibold">{{ $venta->usuario->name }}</span>
                </div>
            </div>
        </div>

        <!-- Método de Pago -->
        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="text-center">
                <i class="fas fa-credit-card text-3xl mb-2"></i>
                <p class="text-sm opacity-90 mb-2">Método de Pago</p>
                <p class="text-2xl font-bold">{{ ucfirst($venta->metodo_pago) }}</p>
            </div>
        </div>

        <!-- Resumen -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Productos:</span>
                    <span class="font-semibold">{{ $venta->detalles->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Unidades:</span>
                    <span class="font-semibold">{{ $venta->detalles->sum('cantidad') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">$ {{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">IGV:</span>
                    <span class="font-semibold">$ {{ number_format($venta->igv, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t">
                    <span class="font-bold text-gray-800">Total:</span>
                    <span class="font-bold text-[#5B8FCC] text-lg">$ {{ number_format($venta->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($venta->cliente)
            <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
                <div class="text-center">
                    <i class="fas fa-star text-3xl mb-2"></i>
                    <p class="text-sm opacity-90 mb-2">Puntos del Cliente</p>
                    <p class="text-3xl font-bold">{{ $venta->cliente->puntos_fidelidad }}</p>
                    <p class="text-xs opacity-75 mt-2">Puntos totales actuales</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
