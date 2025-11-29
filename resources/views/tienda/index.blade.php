@extends('tienda.layout')

@section('title', 'Tienda - Mascotas Felices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Banner -->
    <div class="bg-[#8B7AB8] rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-bold mb-2">¡Bienvenido a Mascotas Felices!</h2>
                <p class="text-xl opacity-90">Encuentra todo lo que necesitas para tu mejor amigo</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-dog text-6xl opacity-75"></i>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('tienda.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text" 
                           name="buscar" 
                           value="{{ request('buscar') }}"
                           placeholder="Buscar productos..."
                           class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Categoría -->
            <div>
                <select name="categoria_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ordenar -->
            <div>
                <select name="orden" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                    <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                    <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Menor precio</option>
                    <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Mayor precio</option>
                </select>
            </div>

            <!-- Botón Buscar -->
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-[#190C7B] text-white px-6 py-2 rounded-lg hover:bg-[#2D1B9E] transition">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Productos Grid -->
    @if($productos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($productos as $producto)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Imagen del producto -->
                    <div class="bg-gray-100 h-48 flex items-center justify-center overflow-hidden">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombre }}"
                                 class="w-full h-full object-cover">
                        @elseif($producto->categoria)
                            <i class="fas fa-{{ 
                                $producto->categoria->nombre == 'Alimentos' ? 'bone' : 
                                ($producto->categoria->nombre == 'Juguetes' ? 'football-ball' : 
                                ($producto->categoria->nombre == 'Accesorios' ? 'tag' : 
                                ($producto->categoria->nombre == 'Higiene' ? 'pump-soap' : 
                                ($producto->categoria->nombre == 'Salud' ? 'heartbeat' : 'box')))) 
                            }} text-6xl text-gray-300"></i>
                        @else
                            <i class="fas fa-box text-6xl text-gray-300"></i>
                        @endif
                    </div>

                    <!-- Información del producto -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xs font-semibold text-[#190C7B] bg-[#EDE9FE] px-2 py-1 rounded">
                                {{ $producto->categoria->nombre ?? 'General' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                Stock: {{ $producto->stock }}
                            </span>
                        </div>

                        <h3 class="font-semibold text-gray-800 mb-2 h-12 overflow-hidden">
                            {{ $producto->nombre }}
                        </h3>

                        @if($producto->descripcion)
                            <p class="text-sm text-gray-600 mb-3 h-10 overflow-hidden">
                                {{ Str::limit($producto->descripcion, 60) }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-[#190C7B]">
                                $ {{ number_format($producto->precio, 2) }}
                            </span>
                        </div>

                        <button onclick="agregarAlCarrito({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }}, {{ $producto->stock }})"
                                class="w-full bg-[#190C7B] text-white py-2 rounded-lg hover:bg-[#2D1B9E] transition duration-300">
                            <i class="fas fa-cart-plus mr-2"></i>
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center">
            {{ $productos->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron productos</h3>
            <p class="text-gray-500">Intenta con otros filtros o términos de búsqueda</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function agregarAlCarrito(id, nombre, precio, stock) {
        // Obtener carrito actual
        let carrito = JSON.parse(localStorage.getItem('carrito') || '[]');
        
        // Buscar si el producto ya existe
        let itemExistente = carrito.find(item => item.id === id);
        
        if (itemExistente) {
            // Verificar stock
            if (itemExistente.cantidad >= stock) {
                alert('No hay suficiente stock disponible');
                return;
            }
            itemExistente.cantidad++;
        } else {
            carrito.push({
                id: id,
                nombre: nombre,
                precio: precio,
                cantidad: 1,
                stock: stock
            });
        }
        
        // Guardar en localStorage
        localStorage.setItem('carrito', JSON.stringify(carrito));
        
        // Disparar evento para actualizar el contador
        window.dispatchEvent(new CustomEvent('carrito-updated'));
        
        // Mostrar notificación
        mostrarNotificacion('Producto agregado al carrito');
    }

    function mostrarNotificacion(mensaje) {
        // Crear notificación
        const notif = document.createElement('div');
        notif.className = 'fixed top-4 right-4 bg-[#F5F3FF]0 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
        notif.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${mensaje}`;
        document.body.appendChild(notif);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notif.remove();
        }, 3000);
    }
</script>
@endpush
