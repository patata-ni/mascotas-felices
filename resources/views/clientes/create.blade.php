@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('clientes.index') }}" class="hover:text-[#190C7B]">Clientes</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Nuevo Cliente</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Nuevo Cliente</h1>
</div>

<form action="{{ route('clientes.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Información Personal -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user text-[#190C7B] mr-2"></i>
                Información Personal
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre') }}"
                           placeholder="Nombre y apellidos completos"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_documento" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('tipo_documento') border-red-500 @enderror">
                        <option value="">Seleccionar...</option>
                        <option value="INE" {{ old('tipo_documento') == 'INE' ? 'selected' : '' }}>DNI</option>
                        <option value="RFC" {{ old('tipo_documento') == 'RFC' ? 'selected' : '' }}>RFC</option>
                        <option value="CURP" {{ old('tipo_documento') == 'CURP' ? 'selected' : '' }}>CURP</option>
                        <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="documento" 
                           value="{{ old('documento') }}"
                           maxlength="20"
                           required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('documento') border-red-500 @enderror">
                    @error('documento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           name="telefono" 
                           value="{{ old('telefono') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('telefono') border-red-500 @enderror">
                    @error('telefono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Nacimiento
                    </label>
                    <input type="date" 
                           name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('fecha_nacimiento') border-red-500 @enderror">
                    @error('fecha_nacimiento')
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
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8] @error('direccion') border-red-500 @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Estado y Puntos -->
        <div class="space-y-6">
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
                    <span class="ml-3 text-sm font-medium text-gray-700">Cliente activo</span>
                </label>
            </div>

            <div class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white">
                <div class="text-center">
                    <i class="fas fa-star text-3xl mb-2"></i>
                    <p class="text-sm opacity-90 mb-2">Puntos de Fidelidad</p>
                    <input type="number" 
                           name="puntos_fidelidad" 
                           value="{{ old('puntos_fidelidad', 0) }}"
                           min="0"
                           class="w-full text-center text-2xl font-bold bg-white/20 border-0 rounded-lg text-white placeholder-white/70 focus:ring-2 focus:ring-white/50">
                    <p class="text-xs opacity-75 mt-2">Se pueden asignar puntos iniciales</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-[#5B8FCC] mt-1 mr-3"></i>
                    <div class="text-sm text-[#190C7B]">
                        <p class="font-semibold mb-1">Programa de Fidelidad</p>
                        <p>Los clientes ganan 1 punto por cada MX$ 100 en compras.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('clientes.index') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-times mr-2"></i>
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition">
            <i class="fas fa-save mr-2"></i>
            Guardar Cliente
        </button>
    </div>
</form>
@endsection
