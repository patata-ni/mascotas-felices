@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('productos.index') }}" class="hover:text-[#190C7B]">Productos</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">{{ $producto->nombre }}</span>
    </div>
    
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Detalle del Producto</h1>
        
        @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <div class="flex space-x-3">
                <a href="{{ route('productos.edit', $producto) }}" 
                   class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('productos.destroy', $producto) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Estás seguro de eliminar este producto?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Imagen y Estado -->
    <div class="space-y-6">
        <!-- Imagen -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                     alt="{{ $producto->nombre }}"
                     class="w-full rounded-lg shadow-md">
            @else
                <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
            @endif
        </div>

        <!-- Estado -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Estado</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Activo:</span>
                    @if($producto->activo)
                        <span class="px-3 py-1 bg-[#EDE9FE] text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Sí
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i>No
                        </span>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Stock:</span>
                    @if($producto->stockBajo())
                        <span class="px-3 py-1 bg-[#FFF5F0] text-[#E89A7B] rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Bajo
                        </span>
                    @else
                        <span class="px-3 py-1 bg-[#EDE9FE] text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Disponible
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Margen -->
        <div class="bg-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-center">
                <i class="fas fa-chart-line text-3xl mb-2"></i>
                <p class="text-sm opacity-90 mb-2">Margen de Ganancia</p>
                <p class="text-4xl font-bold">{{ $producto->margenGanancia() }}%</p>
            </div>
        </div>
    </div>

    <!-- Información Detallada -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-[#190C7B] mr-2"></i>
                Información Básica
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Código</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $producto->codigo }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $producto->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Categoría</p>
                    <p class="text-lg font-semibold text-gray-800">
                        <span class="px-3 py-1 bg-[#EDE9FE] text-[#190C7B] rounded-full text-sm">
                            {{ $producto->categoria->nombre }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Proveedor</p>
                    <p class="text-lg font-semibold text-gray-800">
                        @if($producto->proveedor)
                            {{ $producto->proveedor->nombre }}
                        @else
                            <span class="text-gray-400 text-sm">Sin proveedor</span>
                        @endif
                    </p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Unidad de Medida</p>
                    <p class="text-lg font-semibold text-gray-800 capitalize">{{ $producto->unidad_medida }}</p>
                </div>
                @if($producto->descripcion)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Descripción</p>
                        <p class="text-gray-800">{{ $producto->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Precios -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-dollar-sign text-[#5B8FCC] mr-2"></i>
                Precios
            </h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-[#5B8FCC] mb-1">Precio de Compra</p>
                    <p class="text-3xl font-bold text-[#190C7B]">
                        $ {{ number_format($producto->precio_compra, 2) }}
                    </p>
                </div>
                <div class="bg-[#F5F3FF] rounded-lg p-4">
                    <p class="text-sm text-[#5B8FCC] mb-1">Precio de Venta</p>
                    <p class="text-3xl font-bold text-green-700">
                        $ {{ number_format($producto->precio_venta, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Inventario -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-warehouse text-[#5B8FCC] mr-2"></i>
                Inventario
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Stock Actual</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $producto->stock_actual }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Mín / Máx</p>
                        <p class="text-lg font-semibold text-gray-700">
                            {{ $producto->stock_minimo }} / {{ $producto->stock_maximo }}
                        </p>
                    </div>
                </div>

                <!-- Barra de progreso de stock -->
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Nivel de Stock</span>
                        <span>{{ round(($producto->stock_actual / $producto->stock_maximo) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        @php
                            $porcentaje = ($producto->stock_actual / $producto->stock_maximo) * 100;
                            if($porcentaje <= 25) {
                                $color = 'bg-red-500';
                            } elseif($porcentaje <= 50) {
                                $color = 'bg-[#FFF5F0]0';
                            } else {
                                $color = 'bg-[#F5F3FF]0';
                            }
                        @endphp
                        <div class="{{ $color }} h-4 rounded-full transition-all duration-300" 
                             style="width: {{ min($porcentaje, 100) }}%"></div>
                    </div>
                </div>

                @if($producto->stockBajo())
                    <div class="bg-[#FFF5F0] border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-[#E89A7B] mt-1 mr-3"></i>
                            <div>
                                <p class="font-semibold text-[#E89A7B]">Stock Bajo</p>
                                <p class="text-sm text-[#E89A7B]">El stock actual está por debajo del nivel mínimo. Considera reabastecer.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Fechas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clock text-gray-600 mr-2"></i>
                Información Adicional
            </h3>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Registrado</p>
                    <p class="font-semibold text-gray-800">{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Última actualización</p>
                    <p class="font-semibold text-gray-800">{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
