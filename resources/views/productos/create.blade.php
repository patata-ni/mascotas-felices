@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('productos.index') }}" class="hover:text-[#190C7B]">Productos</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Nuevo Producto</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Nuevo Producto</h1>
</div>

<form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Información Básica -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-info-circle text-[#190C7B] mr-2"></i>
                Información Básica
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="codigo" 
                           value="{{ old('codigo') }}"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('codigo') border-red-500 @enderror"
                           placeholder="Ej: ALM001">
                    @error('codigo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Producto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre') }}"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Comida para perros adultos">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" 
                              rows="3"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('descripcion') border-red-500 @enderror"
                              placeholder="Descripción detallada del producto...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select name="categoria_id" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('categoria_id') border-red-500 @enderror">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proveedor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
                    <select name="proveedor_id" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                        <option value="">Sin proveedor</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Unidad de Medida -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unidad de Medida <span class="text-red-500">*</span>
                    </label>
                    <select name="unidad_medida" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                        <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="bolsa" {{ old('unidad_medida') == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                        <option value="caja" {{ old('unidad_medida') == 'caja' ? 'selected' : '' }}>Caja</option>
                        <option value="paquete" {{ old('unidad_medida') == 'paquete' ? 'selected' : '' }}>Paquete</option>
                        <option value="botella" {{ old('unidad_medida') == 'botella' ? 'selected' : '' }}>Botella</option>
                        <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Imagen y Estado -->
        <div class="space-y-6">
            <!-- Imagen -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-image text-[#190C7B] mr-2"></i>
                    Imagen
                </h2>
                
                <div x-data="{ preview: null }" class="space-y-4">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div x-show="!preview">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                            <p class="text-sm text-gray-600">Selecciona una imagen</p>
                        </div>
                        <img x-show="preview" 
                             :src="preview" 
                             x-cloak
                             class="max-h-48 mx-auto rounded-lg">
                    </div>
                    
                    <input type="file" 
                           name="imagen" 
                           accept="image/*"
                           @change="preview = $event.target.files.length > 0 ? URL.createObjectURL($event.target.files[0]) : null"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F5F3FF] file:text-[#2D1B9E] hover:file:bg-[#EDE9FE]">
                    <p class="text-xs text-gray-500">PNG, JPG hasta 2MB</p>
                </div>
            </div>

            <!-- Estado -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-toggle-on text-[#190C7B] mr-2"></i>
                    Estado
                </h2>
                
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="activo" 
                           value="1"
                           {{ old('activo', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-[#190C7B] border-gray-300 rounded focus:ring-[#4A3DB8]">
                    <span class="ml-3 text-sm font-medium text-gray-700">Producto activo</span>
                </label>
            </div>
        </div>

        <!-- Precios -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-dollar-sign text-[#5B8FCC] mr-2"></i>
                Precios
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Precio de Compra <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" 
                               name="precio_compra" 
                               value="{{ old('precio_compra') }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('precio_compra') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('precio_compra')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Precio de Venta <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" 
                               name="precio_venta" 
                               value="{{ old('precio_venta') }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('precio_venta') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('precio_venta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Stock -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-warehouse text-[#5B8FCC] mr-2"></i>
                Inventario
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Actual <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="stock_actual" 
                           value="{{ old('stock_actual', 0) }}"
                           min="0"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('stock_actual') border-red-500 @enderror">
                    @error('stock_actual')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Mínimo <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="stock_minimo" 
                           value="{{ old('stock_minimo', 0) }}"
                           min="0"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Máximo <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="stock_maximo" 
                           value="{{ old('stock_maximo', 100) }}"
                           min="0"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                </div>
            </div>
        </div>
    </div>

    <!-- Botones -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('productos.index') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-times mr-2"></i>
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
            <i class="fas fa-save mr-2"></i>
            Guardar Producto
        </button>
    </div>
</form>
@endsection
