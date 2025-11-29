@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('proveedores.index') }}" class="hover:text-[#190C7B]">Proveedores</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Editar: {{ $proveedor->nombre }}</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Editar Proveedor</h1>
</div>

<form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Información de la Empresa -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-building text-[#190C7B] mr-2"></i>
                Información de la Empresa
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Empresa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre', $proveedor->nombre) }}"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        RUC <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="ruc" 
                           value="{{ old('ruc', $proveedor->ruc) }}"
                           maxlength="11"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('ruc') border-red-500 @enderror">
                    @error('ruc')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="telefono" 
                           value="{{ old('telefono', $proveedor->telefono) }}"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $proveedor->email) }}"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <textarea name="direccion" 
                              rows="2"
                              required
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('direccion') border-red-500 @enderror">{{ old('direccion', $proveedor->direccion) }}</textarea>
                    @error('direccion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Información de Contacto y Estado -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-user text-[#190C7B] mr-2"></i>
                    Persona de Contacto
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $proveedor->nombre) }}"
                               required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('nombre') border-red-500 @enderror">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-toggle-on text-[#190C7B] mr-2"></i>
                    Estado
                </h2>
                
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="activo" 
                           value="1"
                           {{ old('activo', $proveedor->activo) ? 'checked' : '' }}
                           class="w-5 h-5 text-[#190C7B] border-gray-300 rounded focus:ring-[#4A3DB8]">
                    <span class="ml-3 text-sm font-medium text-gray-700">Proveedor activo</span>
                </label>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Estadísticas</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Productos:</span>
                        <span class="font-semibold">{{ $proveedor->productos->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pedidos:</span>
                        <span class="font-semibold">{{ $proveedor->pedidos->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Registrado:</span>
                        <span class="font-semibold">{{ $proveedor->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('proveedores.index') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-times mr-2"></i>
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
            <i class="fas fa-save mr-2"></i>
            Actualizar Proveedor
        </button>
    </div>
</form>
@endsection
