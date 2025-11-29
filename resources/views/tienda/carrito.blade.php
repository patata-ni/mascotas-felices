@extends('tienda.layout')

@section('title', 'Carrito de Compras - Mascotas Felices')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="carritoPage()">
    <div class="max-w-6xl mx-auto">
        <!-- Título -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-shopping-cart text-[#190C7B] mr-3"></i>
                Carrito de Compras
            </h1>
            <a href="{{ route('tienda.index') }}" class="text-[#190C7B] hover:text-[#2D1B9E]">
                <i class="fas fa-arrow-left mr-2"></i>
                Seguir comprando
            </a>
        </div>

        <!-- Alerta para usuarios no logueados -->
        @if(!session()->has('cliente_id'))
            <div x-show="items.length > 0" class="bg-[#FFF5F0] border-l-4 border-yellow-500 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-2xl text-[#E89A7B]"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-[#E89A7B]">
                            <strong>¡Inicia sesión para completar tu compra!</strong>
                        </p>
                        <p class="text-sm text-[#E89A7B] mt-1">
                            Necesitas tener una cuenta para poder realizar el pago y acumular puntos de fidelidad.
                        </p>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('tienda.login') }}" class="inline-flex items-center px-4 py-2 bg-[#E89A7B] hover:bg-[#D68A6B] text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div x-show="items.length === 0" class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tu carrito está vacío</h3>
            <p class="text-gray-500 mb-6">¡Agrega productos para comenzar tu compra!</p>
            <a href="{{ route('tienda.index') }}" class="inline-block bg-[#190C7B] text-white px-6 py-3 rounded-lg hover:bg-[#2D1B9E] transition">
                <i class="fas fa-store mr-2"></i>
                Ir a la tienda
            </a>
        </div>

        <div x-show="items.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Productos del carrito -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b">
                        <h2 class="font-semibold text-gray-800">Productos (<span x-text="items.length"></span>)</h2>
                    </div>
                    
                    <div class="divide-y">
                        <template x-for="(item, index) in items" :key="item.id">
                            <div class="p-4 flex items-center space-x-4">
                                <!-- Icono del producto -->
                                <div class="bg-gray-100 w-20 h-20 rounded flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-box text-2xl text-gray-400"></i>
                                </div>

                                <!-- Información -->
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800" x-text="item.nombre"></h3>
                                    <p class="text-sm text-gray-500">Precio: $ <span x-text="item.precio.toFixed(2)"></span></p>
                                    <p class="text-xs text-gray-400">Stock disponible: <span x-text="item.stock"></span></p>
                                </div>

                                <!-- Cantidad -->
                                <div class="flex items-center space-x-2">
                                    <button @click="cambiarCantidad(index, -1)" 
                                            class="bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded flex items-center justify-center">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-12 text-center font-semibold" x-text="item.cantidad"></span>
                                    <button @click="cambiarCantidad(index, 1)" 
                                            :disabled="item.cantidad >= item.stock"
                                            :class="item.cantidad >= item.stock ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'"
                                            class="bg-gray-200 w-8 h-8 rounded flex items-center justify-center">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right w-24">
                                    <p class="font-bold text-[#190C7B]">
                                        $ <span x-text="(item.precio * item.cantidad).toFixed(2)"></span>
                                    </p>
                                </div>

                                <!-- Eliminar -->
                                <button @click="eliminarItem(index)" 
                                        class="text-red-500 hover:text-red-700 w-8 h-8">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Resumen del Pedido</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-semibold">$ <span x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        
                        <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-800">
                            <span>Total:</span>
                            <span class="text-[#190C7B]">$ <span x-text="total.toFixed(2)"></span></span>
                        </div>
                    </div>

                    @if(session()->has('cliente_id'))
                        <button @click="mostrarCheckout = true" 
                                class="w-full bg-[#190C7B] text-white py-3 rounded-lg hover:bg-[#2D1B9E] transition duration-300 font-semibold">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceder al Pago
                        </button>
                    @else
                        <a href="{{ route('tienda.login') }}" 
                           class="block w-full bg-[#FFF5F0]0 text-white py-3 rounded-lg hover:bg-[#E89A7B] transition duration-300 font-semibold text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión para Comprar
                        </a>
                    @endif

                    <button @click="vaciarCarrito()" 
                            class="w-full mt-3 bg-red-100 text-red-600 py-2 rounded-lg hover:bg-red-200 transition">
                        <i class="fas fa-trash mr-2"></i>
                        Vaciar Carrito
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Checkout -->
        <div x-show="mostrarCheckout" 
             x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50"
             @click.self="mostrarCheckout = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Completar Compra</h2>
                        <button @click="mostrarCheckout = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>

                    <form @submit.prevent="procesarCompra()">
                        <!-- Datos del cliente -->
                        <div class="mb-6">
                            @if(session()->has('cliente_id'))
                                <!-- Cliente logueado -->
                                <div class="bg-[#F5F3FF] border border-indigo-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-[#190C7B]">Comprando como:</h3>
                                            <p class="text-[#2D1B9E]">{{ session('cliente_nombre') }}</p>
                                            <p class="text-sm text-[#190C7B]">{{ session('cliente_tipo_documento') }}: {{ session('cliente_documento') }}</p>
                                        </div>
                                        <i class="fas fa-user-check text-3xl text-[#5B8FCC]"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Cliente sin login -->
                                <div class="bg-[#FFF5F0] border border-yellow-200 rounded-lg p-4 mb-4">
                                    <p class="text-[#E89A7B] text-sm">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        ¿Ya tienes cuenta? 
                                        <a href="{{ route('tienda.login') }}" class="font-semibold underline">Inicia sesión</a> 
                                        para comprar más rápido y acumular puntos.
                                    </p>
                                </div>
                                
                                <h3 class="font-semibold text-gray-700 mb-4">Datos del Cliente</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre Completo <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="checkout.nombre" required
                                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Tipo de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <select x-model="checkout.tipo_documento" required
                                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                            <option value="INE">DNI</option>
                                            <option value="RFC">RFC</option>
                                            <option value="CURP">CURP</option>
                                            <option value="PASAPORTE">Pasaporte</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" x-model="checkout.documento" required
                                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Teléfono
                                        </label>
                                        <input type="text" x-model="checkout.telefono"
                                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Email
                                        </label>
                                        <input type="email" x-model="checkout.email"
                                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Dirección
                                        </label>
                                        <textarea x-model="checkout.direccion" rows="2"
                                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Método de pago -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-700 mb-4">Método de Pago</h3>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                       :class="checkout.metodo_pago === 'efectivo' ? 'border-indigo-500 bg-[#F5F3FF]' : 'border-gray-300'">
                                    <input type="radio" x-model="checkout.metodo_pago" value="efectivo" class="hidden">
                                    <i class="fas fa-money-bill-wave text-2xl text-[#5B8FCC] mb-2"></i>
                                    <span class="text-sm font-medium">Efectivo</span>
                                </label>

                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                       :class="checkout.metodo_pago === 'tarjeta' ? 'border-indigo-500 bg-[#F5F3FF]' : 'border-gray-300'">
                                    <input type="radio" x-model="checkout.metodo_pago" value="tarjeta" class="hidden">
                                    <i class="fas fa-credit-card text-2xl text-[#5B8FCC] mb-2"></i>
                                    <span class="text-sm font-medium">Tarjeta</span>
                                </label>

                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                       :class="checkout.metodo_pago === 'transferencia' ? 'border-indigo-500 bg-[#F5F3FF]' : 'border-gray-300'">
                                    <input type="radio" x-model="checkout.metodo_pago" value="transferencia" class="hidden">
                                    <i class="fas fa-exchange-alt text-2xl text-[#8B7AB8] mb-2"></i>
                                    <span class="text-sm font-medium">Transfer.</span>
                                </label>

                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                       :class="checkout.metodo_pago === 'oxxo' ? 'border-indigo-500 bg-[#F5F3FF]' : 'border-gray-300'">
                                    <input type="radio" x-model="checkout.metodo_pago" value="oxxo" class="hidden">
                                    <i class="fas fa-store text-2xl text-red-600 mb-2"></i>
                                    <span class="text-sm font-medium">OXXO</span>
                                </label>

                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                       :class="checkout.metodo_pago === 'mercado_pago' ? 'border-indigo-500 bg-[#F5F3FF]' : 'border-gray-300'">
                                    <input type="radio" x-model="checkout.metodo_pago" value="mercado_pago" class="hidden">
                                    <i class="fas fa-mobile-alt text-2xl text-[#5B8FCC] mb-2"></i>
                                    <span class="text-sm font-medium">Mercado Pago</span>
                                </label>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">$ <span x-text="subtotal.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-[#190C7B] border-t pt-2">
                                <span>Total a Pagar:</span>
                                <span>$ <span x-text="total.toFixed(2)"></span></span>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex space-x-3">
                            <button type="button" @click="mostrarCheckout = false"
                                    class="flex-1 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancelar
                            </button>
                            <button type="submit" :disabled="procesando"
                                    :class="procesando ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="flex-1 bg-[#190C7B] text-white px-6 py-3 rounded-lg hover:bg-[#2D1B9E] transition font-semibold">
                                <span x-show="!procesando">
                                    <i class="fas fa-check mr-2"></i>
                                    Confirmar Compra
                                </span>
                                <span x-show="procesando">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function carritoPage() {
        return {
            items: [],
            mostrarCheckout: false,
            procesando: false,
            checkout: {
                nombre: '',
                documento: '',
                tipo_documento: 'INE',
                telefono: '',
                email: '',
                direccion: '',
                metodo_pago: 'efectivo'
            },
            
            init() {
                this.cargarCarrito();
            },
            
            get subtotal() {
                return this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            },
            
            get total() {
                return this.subtotal;
            },
            
            cargarCarrito() {
                const saved = localStorage.getItem('carrito');
                if (saved) {
                    this.items = JSON.parse(saved);
                }
            },
            
            guardarCarrito() {
                localStorage.setItem('carrito', JSON.stringify(this.items));
                window.dispatchEvent(new CustomEvent('carrito-updated'));
            },
            
            cambiarCantidad(index, delta) {
                const item = this.items[index];
                const nuevaCantidad = item.cantidad + delta;
                
                if (nuevaCantidad <= 0) {
                    this.eliminarItem(index);
                } else if (nuevaCantidad <= item.stock) {
                    item.cantidad = nuevaCantidad;
                    this.guardarCarrito();
                } else {
                    alert('No hay suficiente stock disponible');
                }
            },
            
            eliminarItem(index) {
                if (confirm('¿Eliminar este producto del carrito?')) {
                    this.items.splice(index, 1);
                    this.guardarCarrito();
                }
            },
            
            vaciarCarrito() {
                if (confirm('¿Vaciar todo el carrito?')) {
                    this.items = [];
                    this.guardarCarrito();
                }
            },
            
            async procesarCompra() {
                if (this.procesando) return;
                
                this.procesando = true;
                
                try {
                    const response = await fetch('{{ route("tienda.checkout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cliente: {
                                nombre: this.checkout.nombre,
                                documento: this.checkout.documento,
                                tipo_documento: this.checkout.tipo_documento,
                                telefono: this.checkout.telefono,
                                email: this.checkout.email,
                                direccion: this.checkout.direccion
                            },
                            productos: this.items.map(item => ({
                                producto_id: item.id,
                                cantidad: item.cantidad
                            })),
                            metodo_pago: this.checkout.metodo_pago
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Limpiar carrito
                        this.items = [];
                        this.guardarCarrito();
                        
                        // Mostrar mensaje de éxito
                        alert('¡Compra realizada exitosamente!\nNúmero de venta: ' + data.numero_venta);
                        
                        // Redirigir al comprobante
                        window.location.href = '{{ url("tienda/comprobante") }}/' + data.venta_id;
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al procesar la compra. Por favor intenta nuevamente.');
                } finally {
                    this.procesando = false;
                }
            }
        }
    }
</script>
@endpush
