@extends('layouts.app')

@section('title', 'Detalle del Cliente')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('clientes.index') }}" class="hover:text-[#190C7B]">Clientes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">{{ $cliente->nombre }}</span>
    </div>
    
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Detalle del Cliente</h1>
        
        <div class="flex space-x-3">
            <a href="{{ route('clientes.historial', $cliente) }}" 
               class="px-4 py-2 bg-[#8B7AB8] text-white rounded-lg hover:bg-[#9B8AC4] transition">
                <i class="fas fa-history mr-2"></i>Historial
            </a>
            <a href="{{ route('clientes.edit', $cliente) }}" 
               class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <form action="{{ route('clientes.destroy', $cliente) }}" 
                  method="POST" 
                  onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i>Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Tarjetas de Estadísticas -->
    <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-shopping-bag text-2xl opacity-80"></i>
                <span class="text-sm opacity-80">Total</span>
            </div>
            <p class="text-3xl font-bold">{{ $cliente->ventas->count() }}</p>
            <p class="text-sm opacity-90">Compras realizadas</p>
        </div>

        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-dollar-sign text-2xl opacity-80"></i>
                <span class="text-sm opacity-80">Gastado</span>
            </div>
            <p class="text-3xl font-bold">$ {{ number_format($cliente->ventas->sum('total'), 2) }}</p>
            <p class="text-sm opacity-90">Monto total</p>
        </div>

        <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-star text-2xl opacity-80"></i>
                <span class="text-sm opacity-80">Fidelidad</span>
            </div>
            <p class="text-3xl font-bold">{{ $cliente->puntos_fidelidad }}</p>
            <p class="text-sm opacity-90">Puntos acumulados</p>
        </div>

        <div class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-chart-line text-2xl opacity-80"></i>
                <span class="text-sm opacity-80">Promedio</span>
            </div>
            <p class="text-3xl font-bold">
                $ {{ $cliente->ventas->count() > 0 ? number_format($cliente->ventas->sum('total') / $cliente->ventas->count(), 2) : '0.00' }}
            </p>
            <p class="text-sm opacity-90">Por compra</p>
        </div>
    </div>

    <!-- Información Personal -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-user text-[#190C7B] mr-2"></i>
                Información Personal
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre Completo</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $cliente->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">{{ $cliente->tipo_documento }}</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $cliente->documento }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $cliente->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Teléfono</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $cliente->telefono }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Dirección</p>
                    <p class="text-gray-800">{{ $cliente->direccion }}</p>
                </div>
            </div>
        </div>

        <!-- Últimas Compras -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-shopping-cart text-[#5B8FCC] mr-2"></i>
                Últimas Compras
            </h3>
            
            @if($cliente->ventas->count() > 0)
                <div class="space-y-3">
                    @foreach($cliente->ventas->take(5) as $venta)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">Venta #{{ $venta->id }}</p>
                                    <p class="text-sm text-gray-600">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-[#5B8FCC]">$ {{ number_format($venta->total, 2) }}</p>
                                    <span class="px-2 py-1 bg-blue-100 text-[#190C7B] rounded text-xs">
                                        {{ ucfirst($venta->metodo_pago) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ $venta->detalles->count() }} producto(s)</span>
                                <a href="{{ route('ventas.show', $venta) }}" 
                                   class="text-[#190C7B] hover:text-[#2D1B9E]">
                                    Ver detalle <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                    <p>Sin compras registradas</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Estado</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Activo:</span>
                    @if($cliente->activo)
                        <span class="px-3 py-1 bg-[#EDE9FE] text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Sí
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i>No
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock text-gray-600 mr-2"></i>
                Fechas
            </h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Registrado</p>
                    <p class="font-semibold text-gray-800">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-500">{{ $cliente->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Última actualización</p>
                    <p class="font-semibold text-gray-800">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($cliente->ventas->count() > 0)
                    <div>
                        <p class="text-gray-600">Última compra</p>
                        <p class="font-semibold text-gray-800">{{ $cliente->ventas->first()->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $cliente->ventas->first()->created_at->diffForHumans() }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white">
            <div class="text-center">
                <i class="fas fa-gift text-3xl mb-2"></i>
                <p class="text-sm opacity-90 mb-2">Canjeables</p>
                <p class="text-4xl font-bold mb-1">$ {{ number_format($cliente->puntos_fidelidad / 10, 2) }}</p>
                <p class="text-xs opacity-75">Equivalente en descuento</p>
            </div>
        </div>
    </div>
</div>
@endsection
