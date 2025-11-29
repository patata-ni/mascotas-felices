<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Mascotas Felices</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    
    <!-- Navegación Superior -->
    <nav class="bg-[#190C7B] shadow-lg fixed w-full z-30 top-0">
        <div class="mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y Toggle Sidebar -->
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white mr-4 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <i class="fas fa-paw text-white text-2xl mr-2"></i>
                        <span class="text-white font-bold text-xl">Mascotas Felices</span>
                    </a>
                </div>

                <!-- Usuario -->
                <div class="flex items-center space-x-4" x-data="{ userMenuOpen: false }">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center text-white hover:text-[#9B8AC4] focus:outline-none">
                            <i class="fas fa-user-circle text-2xl mr-2"></i>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                <p class="text-xs text-[#190C7B] font-semibold">
                                    {{ ucfirst(Auth::user()->role) }}
                                </p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Mi Perfil
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Configuración
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-white shadow-lg transition-transform duration-300 ease-in-out z-20 overflow-y-auto">
        
        <nav class="mt-5 px-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1
                      {{ request()->routeIs('dashboard') ? 'bg-[#F5F3FF] text-[#190C7B]' : '' }}">
                <i class="fas fa-home mr-3 text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <!-- Productos -->
            <div x-data="{ open: {{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full group flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-3 text-lg"></i>
                        <span class="font-medium">Productos</span>
                    </div>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-sm"></i>
                </button>
                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('productos.index') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg
                              {{ request()->routeIs('productos.index') ? 'text-[#190C7B] bg-[#F5F3FF]' : '' }}">
                        <i class="fas fa-list mr-2"></i> Listado
                    </a>
                    <a href="{{ route('productos.create') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('productos.stock-bajo') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Stock Bajo
                    </a>
                    <a href="{{ route('categorias.index') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-tags mr-2"></i> Categorías
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->esAdministrador() || Auth::user()->esVendedor())
            <!-- Clientes -->
            <a href="{{ route('clientes.index') }}" 
               class="group flex items-center px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1
                      {{ request()->routeIs('clientes.*') ? 'bg-[#F5F3FF] text-[#190C7B]' : '' }}">
                <i class="fas fa-users mr-3 text-lg"></i>
                <span class="font-medium">Clientes</span>
            </a>
            @endif

            @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <!-- Proveedores -->
            <a href="{{ route('proveedores.index') }}" 
               class="group flex items-center px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1
                      {{ request()->routeIs('proveedores.*') ? 'bg-[#F5F3FF] text-[#190C7B]' : '' }}">
                <i class="fas fa-truck mr-3 text-lg"></i>
                <span class="font-medium">Proveedores</span>
            </a>

            <!-- Pedidos -->
            <a href="{{ route('pedidos.index') }}" 
               class="group flex items-center px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1
                      {{ request()->routeIs('pedidos.*') ? 'bg-[#F5F3FF] text-[#190C7B]' : '' }}">
                <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                <span class="font-medium">Pedidos</span>
            </a>
            @endif

            @if(Auth::user()->esAdministrador() || Auth::user()->esVendedor())
            <!-- Ventas -->
            <div x-data="{ open: {{ request()->routeIs('ventas.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full group flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart mr-3 text-lg"></i>
                        <span class="font-medium">Ventas</span>
                    </div>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-sm"></i>
                </button>
                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('ventas.create') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-cash-register mr-2"></i> Punto de Venta
                    </a>
                    <a href="{{ route('ventas.index') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-list mr-2"></i> Historial
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->esAdministrador())
            <!-- Reportes -->
            <div x-data="{ open: {{ request()->routeIs('reportes.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full group flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-[#F5F3FF] hover:text-[#190C7B] rounded-lg mb-1">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line mr-3 text-lg"></i>
                        <span class="font-medium">Reportes</span>
                    </div>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-sm"></i>
                </button>
                <div x-show="open" x-cloak class="ml-4 mt-1 space-y-1">
                   <!-- <a href="{{ route('reportes.index') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a> -->
                    <a href="{{ route('reportes.ventas') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-money-bill-wave mr-2"></i> Ventas
                    </a>
                    <a href="{{ route('reportes.inventario') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-boxes mr-2"></i> Inventario
                    </a>
                    <a href="{{ route('reportes.productos-mas-vendidos') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-star mr-2"></i> Top Productos
                    </a>
                    <a href="{{ route('reportes.clientes') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-user-tie mr-2"></i> Clientes
                    </a>
                    <a href="{{ route('reportes.pedidos') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-shopping-cart mr-2"></i> Pedidos
                    </a>
                    <a href="{{ route('reportes.financiero') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-600 hover:text-[#190C7B] hover:bg-[#F5F3FF] rounded-lg">
                        <i class="fas fa-chart-pie mr-2"></i> Financiero
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </aside>

    <!-- Contenido Principal -->
    <main :class="sidebarOpen ? 'ml-64' : 'ml-0'" 
          class="transition-all duration-300 pt-16 min-h-screen">
        <div class="p-6">
            
            <!-- Alertas -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 class="mb-4 bg-[#EDE9FE] border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-cloak
                 class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <!-- Contenido de la Página -->
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
