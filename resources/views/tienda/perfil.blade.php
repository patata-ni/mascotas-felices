@extends('tienda.layout')

@section('title', 'Mi Perfil - Mascotas Felices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-circle text-[#190C7B] mr-3"></i>
                Mi Perfil
            </h1>
            <a href="{{ route('tienda.index') }}" class="text-[#190C7B] hover:text-[#2D1B9E]">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la tienda
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información del Cliente -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="bg-[#190C7B] text-white rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-4xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $cliente->nombre }}</h2>
                        <p class="text-gray-600">{{ $cliente->tipo_documento }}: {{ $cliente->documento }}</p>
                    </div>

                    <!-- Puntos de Fidelidad -->
                    <div class="bg-[#FFF5F0]0 rounded-lg p-4 mb-4">
                        <div class="text-center text-white">
                            <i class="fas fa-star text-3xl mb-2"></i>
                            <p class="text-sm opacity-90">Puntos de Fidelidad</p>
                            <p class="text-3xl font-bold">{{ $cliente->puntos_fidelidad }}</p>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">
                                <i class="fas fa-shopping-bag mr-2 text-[#190C7B]"></i>
                                Total Compras
                            </span>
                            <span class="font-semibold text-gray-800">$ {{ number_format($cliente->total_compras, 2) }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">
                                <i class="fas fa-receipt mr-2 text-[#190C7B]"></i>
                                Número de Compras
                            </span>
                            <span class="font-semibold text-gray-800">{{ $cliente->ventas->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">
                                <i class="fas fa-calendar mr-2 text-[#190C7B]"></i>
                                Cliente desde
                            </span>
                            <span class="font-semibold text-gray-800">{{ $cliente->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="font-semibold text-gray-700 mb-3">Información de Contacto</h3>
                        <div class="space-y-2 text-sm">
                            @if($cliente->telefono)
                                <p class="text-gray-600">
                                    <i class="fas fa-phone mr-2 text-[#190C7B]"></i>
                                    {{ $cliente->telefono }}
                                </p>
                            @endif
                            
                            @if($cliente->email)
                                <p class="text-gray-600">
                                    <i class="fas fa-envelope mr-2 text-[#190C7B]"></i>
                                    {{ $cliente->email }}
                                </p>
                            @endif
                            
                            @if($cliente->direccion)
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-[#190C7B]"></i>
                                    {{ $cliente->direccion }}
                                </p>
                            @endif

                            @if($cliente->fecha_nacimiento)
                                <p class="text-gray-600">
                                    <i class="fas fa-birthday-cake mr-2 text-[#190C7B]"></i>
                                    {{ $cliente->fecha_nacimiento->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Compras -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-history mr-2 text-[#190C7B]"></i>
                        Historial de Compras
                    </h2>

                    @if($cliente->ventas->count() > 0)
                        <div class="space-y-4">
                            @foreach($cliente->ventas as $venta)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">{{ $venta->numero_venta }}</h3>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $venta->fecha_venta->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-[#190C7B]">$ {{ number_format($venta->total, 2) }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $venta->estado === 'completada' ? 'bg-[#EDE9FE] text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($venta->estado) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Detalles de la venta -->
                                    <div class="border-t pt-3 mt-3">
                                        <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                            <div>
                                                <span class="text-gray-600">Método de pago:</span>
                                                <span class="font-semibold ml-2">{{ strtoupper($venta->metodo_pago) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Productos:</span>
                                                <span class="font-semibold ml-2">{{ $venta->detalles->count() }}</span>
                                            </div>
                                        </div>

                                        <!-- Productos -->
                                        <div class="bg-gray-50 rounded p-3 space-y-2">
                                            @foreach($venta->detalles as $detalle)
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-700">
                                                        {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                                    </span>
                                                    <span class="font-semibold text-gray-800">
                                                        $ {{ number_format($detalle->subtotal, 2) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Botón ver comprobante -->
                                    <div class="mt-3">
                                        <a href="{{ route('tienda.comprobante', $venta->id) }}" 
                                           target="_blank"
                                           class="inline-block bg-[#EDE9FE] text-[#2D1B9E] px-4 py-2 rounded-lg hover:bg-[#DDD6FE] transition text-sm">
                                            <i class="fas fa-file-invoice mr-2"></i>
                                            Ver Comprobante
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($cliente->ventas->count() >= 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">Mostrando las últimas 10 compras</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aún no has realizado compras</h3>
                            <p class="text-gray-500 mb-6">¡Explora nuestra tienda y encuentra lo mejor para tu mascota!</p>
                            <a href="{{ route('tienda.index') }}" 
                               class="inline-block bg-[#190C7B] text-white px-6 py-3 rounded-lg hover:bg-[#2D1B9E] transition">
                                <i class="fas fa-store mr-2"></i>
                                Ir a la Tienda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
