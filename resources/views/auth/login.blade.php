@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-[#190C7B] rounded-full mb-4">
            <i class="fas fa-paw text-white text-4xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Mascotas Felices</h1>
        <p class="text-gray-600 mt-2">Sistema de Gestión</p>
    </div>

    <!-- Mensajes de Error -->
    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('status'))
    <div class="mb-6 bg-[#EDE9FE] border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ session('status') }}
    </div>
    @endif

    <!-- Formulario -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                Correo Electrónico
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   value="{{ old('email') }}"
                   required 
                   autofocus
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent transition"
                   placeholder="correo@ejemplo.com">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                Contraseña
            </label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A3DB8] focus:border-transparent transition"
                   placeholder="••••••••">
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="remember" 
                       class="w-4 h-4 text-[#190C7B] border-gray-300 rounded focus:ring-[#4A3DB8]">
                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
            </label>
            
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" 
               class="text-sm text-[#190C7B] hover:text-[#2D1B9E]">
                ¿Olvidaste tu contraseña?
            </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-[#190C7B] text-white py-3 rounded-lg font-semibold hover:bg-[#2D1B9E] focus:outline-none focus:ring-2 focus:ring-[#4A3DB8] focus:ring-offset-2 transition-colors">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Iniciar Sesión
        </button>
    </form>

    <!-- Usuarios de Prueba -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <p class="text-xs text-gray-600 text-center mb-3">Usuarios de prueba:</p>
        <div class="space-y-2 text-xs">
            <div class="flex justify-between bg-gray-50 p-2 rounded">
                <span class="text-gray-600">Administrador:</span>
                <span class="font-mono text-gray-800">admin@mascotasfelices.com</span>
            </div>
            <div class="flex justify-between bg-gray-50 p-2 rounded">
                <span class="text-gray-600">Vendedor:</span>
                <span class="font-mono text-gray-800">vendedor@mascotasfelices.com</span>
            </div>
            <div class="flex justify-between bg-gray-50 p-2 rounded">
                <span class="text-gray-600">Contraseña:</span>
                <span class="font-mono text-gray-800">password</span>
            </div>
        </div>
    </div>
</div>
@endsection
