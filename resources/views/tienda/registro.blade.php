@extends('tienda.layout')

@section('title', 'Registrarse - Mascotas Felices')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#EDE9FE] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <!-- Logo y Título -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="bg-[#190C7B] text-white rounded-full w-20 h-20 flex items-center justify-center">
                    <i class="fas fa-paw text-4xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Crear Cuenta</h2>
            <p class="mt-2 text-gray-600">Regístrate para realizar tus compras más rápido</p>
        </div>

        <!-- Formulario de Registro -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('tienda.registro.post') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre Completo -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               required
                               maxlength="150"
                               placeholder="Ingresa tu nombre completo"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Documento <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_documento" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                            <option value="INE" {{ old('tipo_documento') == 'INE' ? 'selected' : '' }}>DNI</option>
                            <option value="RFC" {{ old('tipo_documento') == 'RFC' ? 'selected' : '' }}>RFC</option>
                            <option value="CURP" {{ old('tipo_documento') == 'CURP' ? 'selected' : '' }}>CURP</option>
                            <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('tipo_documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="documento" 
                               value="{{ old('documento') }}"
                               required
                               maxlength="20"
                               placeholder="Ej: 12345678"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('documento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="text" 
                               name="telefono" 
                               value="{{ old('telefono') }}"
                               maxlength="20"
                               placeholder="Ej: 5512345678"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required
                               maxlength="100"
                               placeholder="correo@ejemplo.com"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               required
                               minlength="6"
                               placeholder="Mínimo 6 caracteres"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               required
                               minlength="6"
                               placeholder="Repite tu contraseña"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento
                        </label>
                        <input type="date" 
                               name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">
                        @error('fecha_nacimiento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea name="direccion" 
                                  rows="2"
                                  placeholder="Ingresa tu dirección completa"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent">{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Términos y condiciones -->
                <div class="mt-6">
                    <label class="flex items-start">
                        <input type="checkbox" 
                               name="acepta_terminos" 
                               required
                               class="mt-1 w-4 h-4 text-[#190C7B] border-gray-300 rounded focus:ring-[#4A3DB8]">
                        <span class="ml-2 text-sm text-gray-600">
                            Acepto los términos y condiciones y la política de privacidad de Mascotas Felices
                        </span>
                    </label>
                    @error('acepta_terminos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botón Registrarse -->
                <button type="submit" 
                        class="w-full mt-6 bg-[#190C7B] text-white py-3 rounded-lg hover:bg-[#2D1B9E] transition duration-300 font-semibold shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Cuenta
                </button>
            </form>

            <!-- Separador -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">¿Ya tienes cuenta?</span>
                </div>
            </div>

            <!-- Botón Login -->
            <a href="{{ route('tienda.login') }}" 
               class="block w-full text-center bg-white border-2 border-indigo-600 text-[#190C7B] py-3 rounded-lg hover:bg-[#F5F3FF] transition duration-300 font-semibold">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Iniciar Sesión
            </a>

            <!-- Link volver -->
            <div class="mt-6 text-center">
                <a href="{{ route('tienda.index') }}" class="text-[#190C7B] hover:text-[#2D1B9E] text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a la tienda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
