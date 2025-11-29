<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tienda - Mascotas Felices')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-[#190C7B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('tienda.index') }}" class="flex items-center space-x-3">
                    <i class="fas fa-paw text-3xl"></i>
                    <div>
                        <h1 class="text-2xl font-bold">Mascotas Felices</h1>
                        <p class="text-xs opacity-90">Todo para tu mascota</p>
                    </div>
                </a>
                
                <div class="flex items-center space-x-4">
                    <!-- Cliente logueado -->
                    @if(session()->has('cliente_id'))
                        <div class="hidden md:flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-lg">
                            <i class="fas fa-user-circle text-xl"></i>
                            <div class="text-sm">
                                <p class="font-semibold">{{ session('cliente_nombre') }}</p>
                                <p class="text-xs opacity-90">
                                    <i class="fas fa-star text-[#E89A7B] mr-1"></i>
                                    {{ session('cliente_puntos', 0) }} puntos
                                </p>
                            </div>
                        </div>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="hover:bg-white/10 px-3 py-2 rounded-lg transition">
                                <i class="fas fa-user text-xl"></i>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                                <a href="{{ route('tienda.perfil') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5F3FF]">
                                    <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                                </a>
                                <form method="POST" action="{{ route('tienda.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-[#F5F3FF]">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('tienda.login') }}" class="hover:bg-white/10 px-4 py-2 rounded-lg transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="hidden md:inline">Ingresar</span>
                        </a>
                    @endif

                    <!-- Carrito -->
                    <a href="{{ route('tienda.carrito') }}" 
                       class="relative hover:bg-white/10 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <span x-data="carrito" 
                              x-text="items.length" 
                              x-show="items.length > 0"
                              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                        </span>
                    </a>

                    <!-- Admin (solo si es usuario del sistema) -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:bg-white/10 px-3 py-2 rounded-lg transition">
                            <i class="fas fa-user-shield text-xl"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Notificaciones -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-20 right-4 bg-[#F5F3FF]0 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-20 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <p>{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#190C7B] text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Mascotas Felices</h3>
                    <p class="text-[#DDD6FE]">
                        La mejor tienda para el cuidado y felicidad de tu mascota.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-[#DDD6FE]">
                        <li><i class="fas fa-phone mr-2"></i> (+52) 782 873 2342</li>
                        <li><i class="fas fa-envelope mr-2"></i> ventas@mascotasfelices.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Veracruz,Mexico</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Horario</h3>
                    <ul class="space-y-2 text-[#DDD6FE]">
                        <li>Lunes a Viernes: 9:00 AM - 7:00 PM</li>
                        <li>Sábados: 9:00 AM - 5:00 PM</li>
                        <li>Domingos: 10:00 AM - 2:00 PM</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#4A3DB8] mt-8 pt-8 text-center text-[#DDD6FE]">
                <p>&copy; 2025 Mascotas Felices. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carrito', () => ({
                items: [],
                
                init() {
                    const saved = localStorage.getItem('carrito');
                    if (saved) {
                        this.items = JSON.parse(saved);
                    }
                    
                    // Escuchar cambios en el carrito
                    window.addEventListener('carrito-updated', () => {
                        const saved = localStorage.getItem('carrito');
                        if (saved) {
                            this.items = JSON.parse(saved);
                        }
                    });
                }
            }))
        });
    </script>

    @stack('scripts')
</body>
</html>
