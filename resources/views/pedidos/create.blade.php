@extends('layouts.app')

@section('title', 'Nuevo Pedido')

@section('content')
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-600 mb-4">
        <a href="{{ route('pedidos.index') }}" class="hover:text-[#190C7B]">Pedidos</a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-800">Nuevo Pedido</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Nuevo Pedido a Proveedor</h1>
</div>

<div x-data="pedidoForm()" x-init="init()">
    <form @submit.prevent="guardarPedido()">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Información del Pedido -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Selección de Proveedor -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-truck text-[#190C7B] mr-2"></i>
                        Información del Pedido
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Proveedor <span class="text-red-500">*</span>
                            </label>
                            <select x-model="proveedorId" 
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                <option value="">Selecciona un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Esperada</label>
                            <input type="date" 
                                   x-model="fechaEsperada"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                            <textarea x-model="observaciones" 
                                      rows="2"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]"
                                      placeholder="Notas adicionales del pedido..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-box text-[#190C7B] mr-2"></i>
                        Productos del Pedido
                    </h3>

                    <!-- Agregar Producto -->
                    <div class="grid grid-cols-12 gap-3 mb-4">
                        <div class="col-span-6">
                            <select x-model="nuevoProducto.id" 
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                                <option value="">Selecciona un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-precio="{{ $producto->precio_compra }}"
                                            data-nombre="{{ $producto->nombre }}">
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock_actual }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" 
                                   x-model="nuevoProducto.cantidad"
                                   min="1"
                                   placeholder="Cantidad"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                        </div>
                        <div class="col-span-3">
                            <button type="button" 
                                    @click="agregarProducto()"
                                    class="w-full px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                                <i class="fas fa-plus mr-2"></i>Agregar
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Productos -->
                    <div class="space-y-3">
                        <template x-if="detalles.length === 0">
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-2"></i>
                                <p class="text-sm">No hay productos agregados</p>
                            </div>
                        </template>

                        <template x-for="(detalle, index) in detalles" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800" x-text="detalle.nombre"></p>
                                    </div>
                                    <button type="button" 
                                            @click="eliminarProducto(index)" 
                                            class="text-red-600 hover:text-red-800 ml-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Cantidad</label>
                                        <input type="number" 
                                               x-model="detalle.cantidad"
                                               min="1"
                                               class="w-full px-3 py-2 border rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Precio Unit.</label>
                                        <input type="number" 
                                               x-model="detalle.precio"
                                               step="0.01"
                                               min="0"
                                               class="w-full px-3 py-2 border rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Subtotal</label>
                                        <p class="font-bold text-gray-800 mt-2">
                                            $ <span x-text="(detalle.cantidad * detalle.precio).toFixed(2)"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-file-invoice-dollar text-[#5B8FCC] mr-2"></i>
                        Resumen del Pedido
                    </h3>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Productos:</span>
                            <span class="font-semibold" x-text="detalles.length"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Unidades:</span>
                            <span class="font-semibold" x-text="totalUnidades"></span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-[#190C7B]">$ <span x-text="total.toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="space-y-3">
                        <button type="submit" 
                                :disabled="detalles.length === 0 || !proveedorId"
                                :class="{'opacity-50 cursor-not-allowed': detalles.length === 0 || !proveedorId}"
                                class="w-full px-4 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition font-semibold">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Pedido
                        </button>
                        <a href="{{ route('pedidos.index') }}" 
                           class="block text-center w-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>

                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-[#5B8FCC] mt-1 mr-3"></i>
                            <div class="text-sm text-[#190C7B]">
                                <p class="font-semibold mb-1">Información</p>
                                <p>El pedido quedará en estado pendiente hasta que sea recibido.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function pedidoForm() {
    return {
        proveedorId: '',
        fechaEsperada: '',
        observaciones: '',
        detalles: [],
        nuevoProducto: {
            id: '',
            cantidad: 1
        },

        init() {
            @if(isset($productoSeleccionado) && $productoSeleccionado)
                // Pre-cargar producto desde stock bajo
                const productoId = '{{ $productoSeleccionado->id }}';
                const productoNombre = '{{ $productoSeleccionado->nombre }}';
                const productoPrecio = {{ $productoSeleccionado->precio_compra }};
                const cantidadSugerida = {{ $productoSeleccionado->stock_minimo - $productoSeleccionado->stock_actual }};
                
                // Si el proveedor del producto está disponible, pre-seleccionarlo
                @if($productoSeleccionado->proveedor_id)
                    this.proveedorId = '{{ $productoSeleccionado->proveedor_id }}';
                @endif
                
                // Agregar el producto automáticamente
                this.detalles.push({
                    producto_id: productoId,
                    nombre: productoNombre,
                    cantidad: Math.max(cantidadSugerida, 1),
                    precio: productoPrecio
                });
            @endif
        },

        agregarProducto() {
            if (!this.nuevoProducto.id || !this.nuevoProducto.cantidad) {
                alert('Selecciona un producto y cantidad');
                return;
            }

            const select = document.querySelector('select[x-model="nuevoProducto.id"]');
            const option = select.options[select.selectedIndex];
            
            if (this.detalles.find(d => d.producto_id == this.nuevoProducto.id)) {
                alert('Este producto ya está en el pedido');
                return;
            }

            this.detalles.push({
                producto_id: this.nuevoProducto.id,
                nombre: option.dataset.nombre,
                cantidad: parseInt(this.nuevoProducto.cantidad),
                precio: parseFloat(option.dataset.precio)
            });

            this.nuevoProducto = { id: '', cantidad: 1 };
        },

        eliminarProducto(index) {
            this.detalles.splice(index, 1);
        },

        get totalUnidades() {
            return this.detalles.reduce((sum, d) => sum + parseInt(d.cantidad), 0);
        },

        get total() {
            return this.detalles.reduce((sum, d) => sum + (d.cantidad * d.precio), 0);
        },

        async guardarPedido() {
            if (!this.proveedorId || this.detalles.length === 0) {
                alert('Completa todos los campos requeridos');
                return;
            }

            const formData = {
                proveedor_id: this.proveedorId,
                fecha_esperada: this.fechaEsperada || null,
                observaciones: this.observaciones,
                detalles: this.detalles.map(d => ({
                    producto_id: d.producto_id,
                    cantidad: d.cantidad,
                    precio_unitario: d.precio
                }))
            };

            try {
                const response = await fetch('{{ route("pedidos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pedido registrado exitosamente');
                    window.location.href = '{{ route("pedidos.index") }}/' + data.id;
                } else {
                    alert('Error: ' + (data.message || 'No se pudo registrar el pedido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión: ' + error.message);
            }
        }
    }
}
</script>
@endsection
