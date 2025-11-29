@extends('tienda.layout')

@section('title', 'Iniciar Sesión - Mascotas Felices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <!-- Encabezado -->
        <div class="text-center mb-8">
            <div class="bg-[#190C7B] w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-circle text-4xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Iniciar Sesión</h1>
            <p class="text-gray-600">Accede a tu cuenta para realizar compras</p>
        </div>

        <!-- Formulario de Login -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Error al iniciar sesión</h3>
                            <ul class="mt-2 text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-[#F5F3FF] border-l-4 border-[#5B8FCC] p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-check-circle text-[#5B8FCC] mr-3 mt-0.5"></i>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('tienda.login.post') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-[#190C7B]"></i>
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent transition"
                           placeholder="tu@email.com">
                </div>

                <!-- Contraseña -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-[#190C7B]"></i>
                        Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#190C7B] focus:ring-[#4A3DB8]">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                    <a href="#" class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <!-- Botón de Login -->
                <button type="submit" 
                        class="w-full bg-[#190C7B] text-white py-3 rounded-lg hover:bg-[#2D1B9E] transition duration-300 font-semibold shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>

            <!-- Separador -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">¿No tienes cuenta?</span>
                </div>
            </div>

            <!-- Link a Registro -->
            <a href="{{ route('tienda.registro') }}" 
               class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition font-medium">
                <i class="fas fa-user-plus mr-2"></i>
                Crear Cuenta Nueva
            </a>

            <!-- Volver a la tienda -->
            <div class="mt-6 text-center">
                <a href="{{ route('tienda.index') }}" class="text-sm text-gray-600 hover:text-[#190C7B] transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a la tienda
                </a>
            </div>
        </div>

        <!-- Beneficios -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="bg-[#FFF5F0] w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-star text-[#E89A7B]"></i>
                </div>
                <p class="text-xs text-gray-600">Acumula puntos</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-shipping-fast text-[#5B8FCC]"></i>
                </div>
                <p class="text-xs text-gray-600">Compra rápida</p>
            </div>
            <div class="text-center">
                <div class="bg-[#EDE9FE] w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-history text-[#5B8FCC]"></i>
                </div>
                <p class="text-xs text-gray-600">Historial de compras</p>
            </div>
        </div>
    </div>
</div>
@endsection