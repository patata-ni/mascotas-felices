@extends('tienda.layout')

@section('title', 'Comprobante de Compra - Mascotas Felices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Botones de acción -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('tienda.index') }}" class="text-[#190C7B] hover:text-[#2D1B9E]">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la tienda
            </a>
            <button onclick="window.print()" class="bg-[#190C7B] text-white px-4 py-2 rounded-lg hover:bg-[#2D1B9E] transition">
                <i class="fas fa-print mr-2"></i>
                Imprimir
            </button>
        </div>

        <!-- Comprobante -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden" id="comprobante">
            <!-- Encabezado -->
            <div class="bg-[#190C7B] text-white p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <i class="fas fa-paw text-3xl"></i>
                            <div>
                                <h1 class="text-2xl font-bold">Mascotas Felices</h1>
                                <p class="text-sm opacity-90">RUC: 20123456789</p>
                            </div>
                        </div>
                        <p class="text-sm opacity-90">Veracruz, México</p>
                        <p class="text-sm opacity-90">Teléfono: (01) 234-5678</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-[#190C7B] px-6 py-3 rounded-lg">
                            <p class="text-xs font-semibold">BOLETA DE VENTA</p>
                            <p class="text-2xl font-bold">{{ $venta->numero_venta }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del cliente y venta -->
            <div class="p-8 border-b">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Cliente:</h3>
                        <div class="space-y-1 text-gray-600">
                            <p><strong>Nombre:</strong> {{ $venta->cliente->nombre }}</p>
                            <p><strong>{{ $venta->cliente->tipo_documento }}:</strong> {{ $venta->cliente->documento }}</p>
                            @if($venta->cliente->telefono)
                                <p><strong>Teléfono:</strong> {{ $venta->cliente->telefono }}</p>
                            @endif
                            @if($venta->cliente->email)
                                <p><strong>Email:</strong> {{ $venta->cliente->email }}</p>
                            @endif
                            @if($venta->cliente->direccion)
                                <p><strong>Dirección:</strong> {{ $venta->cliente->direccion }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">Detalles de la Venta:</h3>
                        <div class="space-y-1 text-gray-600">
                            <p><strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y H:i') }}</p>
                            <p><strong>Método de Pago:</strong> 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $venta->metodo_pago === 'efectivo' ? 'bg-[#EDE9FE] text-green-800' : 
                                       ($venta->metodo_pago === 'tarjeta' ? 'bg-blue-100 text-[#190C7B]' : 
                                       ($venta->metodo_pago === 'yape' ? 'bg-[#F3E8FF] text-[#8B7AB8]' : 
                                       ($venta->metodo_pago === 'plin' ? 'bg-[#EDE9FE] text-[#5B8FCC]' : 
                                       'bg-gray-100 text-gray-800'))) }}">
                                    {{ strtoupper($venta->metodo_pago) }}
                                </span>
                            </p>
                            <p><strong>Estado:</strong> 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $venta->estado === 'completada' ? 'bg-[#EDE9FE] text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper($venta->estado) }}
                                </span>
                            </p>
                            <p><strong>Atendido por:</strong> {{ $venta->usuario->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="p-8">
                <h3 class="font-semibold text-gray-700 mb-4">Productos:</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 px-4">Código</th>
                                <th class="text-left py-3 px-4">Producto</th>
                                <th class="text-center py-3 px-4">Cantidad</th>
                                <th class="text-right py-3 px-4">Precio Unit.</th>
                                <th class="text-right py-3 px-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 px-4 text-gray-600">{{ $detalle->producto->codigo }}</td>
                                    <td class="py-3 px-4 text-gray-800">{{ $detalle->producto->nombre }}</td>
                                    <td class="py-3 px-4 text-center text-gray-600">{{ $detalle->cantidad }}</td>
                                    <td class="py-3 px-4 text-right text-gray-600">$ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">$ {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totales -->
                <div class="mt-6 flex justify-end">
                    <div class="w-full md:w-1/2 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-semibold">$ {{ number_format($venta->subtotal, 2) }}</span>
                        </div>
                        
                        @if($venta->descuento > 0)
                            <div class="flex justify-between text-[#5B8FCC]">
                                <span>Descuento:</span>
                                <span class="font-semibold">- $ {{ number_format($venta->descuento, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between text-xl font-bold text-[#190C7B] border-t-2 pt-2">
                            <span>Total:</span>
                            <span>$ {{ number_format($venta->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Puntos de fidelidad -->
            @if($venta->cliente->puntos_fidelidad > 0)
                <div class="bg-[#E89A7B] p-6 text-center rounded-b-lg">
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-star text-3xl text-white"></i>
                        <div class="text-white">
                            <p class="text-sm opacity-90">El cliente ahora tiene</p>
                            <p class="text-2xl font-bold">{{ $venta->cliente->puntos_fidelidad }} puntos de fidelidad</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pie -->
            <div class="bg-gray-50 p-6 text-center text-gray-600 text-sm">
                <p class="mb-2">¡Gracias por tu compra!</p>
                <p>Conserva este comprobante para cualquier reclamo o cambio</p>
                <p class="mt-2 text-xs">Este documento no tiene validez tributaria</p>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <div class="mt-6 bg-[#F5F3FF] border border-green-200 rounded-lg p-6 text-center">
            <i class="fas fa-check-circle text-4xl text-[#5B8FCC] mb-3"></i>
            <h3 class="text-xl font-bold text-green-800 mb-2">¡Compra realizada con éxito!</h3>
            <p class="text-green-700">Tu pedido ha sido procesado correctamente</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #comprobante, #comprobante * {
            visibility: visible;
        }
        #comprobante {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endpush
