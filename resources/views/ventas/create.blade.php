@extends('layouts.app')

@section('title', 'Punto de Venta')

@section('content')
<div x-data="pos()" x-init="init()" class="space-y-6">
    
    <!-- Encabezado -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-cash-register text-[#5B8FCC] mr-2"></i>
                Punto de Venta
            </h1>
            <p class="text-gray-600 mt-1">Registra ventas y gestiona el carrito de compras</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Vendedor</p>
            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Productos y Búsqueda -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Búsqueda -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <input type="text" 
                               x-model="busqueda"
                               @input="buscarProductos()"
                               placeholder="Buscar productos por nombre o código..." 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-[#5B8FCC]">
                    </div>
                    <select x-model="categoriaFiltro" 
                            @change="buscarProductos()"
                            class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Grid de Productos -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Productos Disponibles</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto">
                    <template x-for="producto in productosFiltrados" :key="producto.id">
                        <div @click="agregarAlCarrito(producto)" 
                             class="border-2 border-gray-200 rounded-lg p-4 hover:border-[#5B8FCC] hover:shadow-lg transition cursor-pointer"
                             :class="{'opacity-50 cursor-not-allowed': producto.stock_actual <= 0}">
                            <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                                <template x-if="producto.imagen">
                                    <img :src="`/storage/${producto.imagen}`" 
                                         :alt="producto.nombre"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!producto.imagen">
                                    <i class="fas fa-box text-gray-400 text-3xl"></i>
                                </template>
                            </div>
                            <p class="font-semibold text-gray-800 text-sm mb-1 truncate" x-text="producto.nombre"></p>
                            <p class="text-xs text-gray-500 mb-2" x-text="producto.codigo"></p>
                            <div class="flex justify-between items-center">
                                <p class="text-[#5B8FCC] font-bold">$ <span x-text="producto.precio_venta"></span></p>
                                <p class="text-xs text-gray-600">
                                    Stock: <span x-text="producto.stock_actual"></span>
                                </p>
                            </div>
                            <template x-if="producto.stock_actual <= 0">
                                <p class="text-xs text-red-600 font-medium mt-2">Agotado</p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Carrito -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-shopping-cart text-[#5B8FCC] mr-2"></i>
                    Carrito de Compras
                </h3>

                <!-- Selección de Cliente -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <select x-model="clienteId" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Cliente General</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">
                                {{ $cliente->nombre }} {{ $cliente->apellidos }} - {{ $cliente->dni }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Items del Carrito -->
                <div class="space-y-3 mb-4 max-h-[300px] overflow-y-auto">
                    <template x-if="carrito.length === 0">
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                            <p class="text-sm">Carrito vacío</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in carrito" :key="index">
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <p class="font-semibold text-sm text-gray-800" x-text="item.nombre"></p>
                                <button @click="eliminarDelCarrito(index)" 
                                        class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">$ <span x-text="item.precio"></span> c/u</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button @click="item.cantidad > 1 ? item.cantidad-- : null" 
                                            class="w-7 h-7 bg-gray-200 rounded hover:bg-gray-300">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" 
                                           x-model="item.cantidad"
                                           min="1"
                                           :max="item.stock_disponible"
                                           class="w-16 text-center border rounded py-1">
                                    <button @click="item.cantidad < item.stock_disponible ? item.cantidad++ : null" 
                                            class="w-7 h-7 bg-gray-200 rounded hover:bg-gray-300">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <p class="font-bold text-gray-800">
                                    $ <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Totales -->
                <div class="border-t pt-4 space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold">$ <span x-text="subtotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>Total:</span>
                        <span class="text-[#5B8FCC]">$ <span x-text="total.toFixed(2)"></span></span>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                    <select x-model="metodoPago" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia SPEI</option>
                        <option value="oxxo">OXXO</option>
                        <option value="mercado_pago">Mercado Pago</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="space-y-2">
                    <button @click="procesarVenta()" 
                            :disabled="carrito.length === 0"
                            :class="{'opacity-50 cursor-not-allowed': carrito.length === 0}"
                            class="w-full px-4 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition font-semibold">
                        <i class="fas fa-check mr-2"></i>
                        Procesar Venta
                    </button>
                    <button @click="limpiarCarrito()" 
                            class="w-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-trash mr-2"></i>
                        Limpiar Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function pos() {
    return {
        productos: @json($productos),
        carrito: [],
        busqueda: '',
        categoriaFiltro: '',
        clienteId: '',
        metodoPago: 'efectivo',
        productosFiltrados: [],

        init() {
            this.productosFiltrados = this.productos;
        },

        buscarProductos() {
            this.productosFiltrados = this.productos.filter(p => {
                const matchBusqueda = p.nombre.toLowerCase().includes(this.busqueda.toLowerCase()) ||
                                    p.codigo.toLowerCase().includes(this.busqueda.toLowerCase());
                const matchCategoria = !this.categoriaFiltro || p.categoria_id == this.categoriaFiltro;
                return matchBusqueda && matchCategoria && p.activo;
            });
        },

        agregarAlCarrito(producto) {
            if (producto.stock_actual <= 0) {
                alert('Producto sin stock disponible');
                return;
            }

            const index = this.carrito.findIndex(item => item.id === producto.id);
            
            if (index !== -1) {
                if (this.carrito[index].cantidad < producto.stock_actual) {
                    this.carrito[index].cantidad++;
                } else {
                    alert('No hay más stock disponible');
                }
            } else {
                this.carrito.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio_venta),
                    cantidad: 1,
                    stock_disponible: producto.stock_actual
                });
            }
        },

        eliminarDelCarrito(index) {
            this.carrito.splice(index, 1);
        },

        limpiarCarrito() {
            if (confirm('¿Limpiar el carrito?')) {
                this.carrito = [];
                this.clienteId = '';
            }
        },

        get subtotal() {
            return this.carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        },

        get total() {
            return this.subtotal;
        },

        async procesarVenta() {
            if (this.carrito.length === 0) {
                alert('Debe agregar al menos un producto al carrito');
                return;
            }

            if (!this.metodoPago) {
                alert('Debe seleccionar un método de pago');
                return;
            }

            if (this.procesando) return;
            this.procesando = true;

            try {
                const formData = {
                    cliente_id: this.clienteId || null,
                    metodo_pago: this.metodoPago,
                    detalles: this.carrito.map(item => ({
                        producto_id: item.id,
                        cantidad: item.cantidad,
                        precio_unitario: item.precio
                    }))
                };

                const response = await fetch('{{ route("ventas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Venta registrada exitosamente');
                    this.limpiarCarrito();
                    this.clienteId = null;
                    this.metodoPago = '';
                    // Redirigir al detalle de la venta
                    window.location.href = '/ventas/' + result.id;
                } else {
                    alert(result.message || 'Error al procesar la venta');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la venta');
            } finally {
                this.procesando = false;
            }
        }
    }
}
</script>
@endsection
