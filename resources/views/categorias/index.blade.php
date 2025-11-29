@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Categorías</h1>
            <p class="text-gray-600 mt-1">Gestión de categorías de productos</p>
        </div>
        @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <button @click="$dispatch('open-modal', 'crear-categoria')" 
                    class="px-4 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Nueva Categoría
            </button>
        @endif
    </div>
</div>

<!-- Grid de Categorías -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categorias as $categoria)
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tag text-[#190C7B] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $categoria->nombre }}</h3>
                        <p class="text-sm text-gray-500">{{ $categoria->productos->count() }} productos</p>
                    </div>
                </div>
                @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
                    <div class="flex space-x-2">
                        <button @click="editarCategoria({{ $categoria->id }}, '{{ $categoria->nombre }}', '{{ $categoria->descripcion }}')" 
                                class="text-[#190C7B] hover:text-[#2D1B9E]">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('categorias.destroy', $categoria) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Eliminar esta categoría?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            
            @if($categoria->descripcion)
                <p class="text-sm text-gray-600 mb-4">{{ $categoria->descripcion }}</p>
            @endif

            <div class="border-t pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Stock Total:</span>
                    <span class="font-semibold">{{ $categoria->productos->sum('stock_actual') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-600">Valor Inventario:</span>
                    <span class="font-semibold text-[#5B8FCC]">
                        $ {{ number_format($categoria->productos->sum(function($p) { return $p->precio_venta * $p->stock_actual; }), 2) }}
                    </span>
                </div>
            </div>

            <a href="{{ route('productos.index', ['categoria' => $categoria->id]) }}" 
               class="mt-4 block text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-eye mr-2"></i>Ver Productos
            </a>
        </div>
    @empty
        <div class="col-span-3 text-center py-12 text-gray-500">
            <i class="fas fa-tags text-4xl mb-3 text-gray-300"></i>
            <p>No hay categorías registradas</p>
        </div>
    @endforelse
</div>

<!-- Modal Crear -->
<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail === 'crear-categoria') open = true"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div @click="open = false" class="fixed inset-0 bg-black opacity-50"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Nueva Categoría</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" 
                                  rows="3"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            @click="open = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div x-data="{ 
        open: false, 
        categoriaId: null, 
        nombre: '', 
        descripcion: '' 
     }" 
     @editar-categoria.window="
        open = true;
        categoriaId = $event.detail.id;
        nombre = $event.detail.nombre;
        descripcion = $event.detail.descripcion;
     "
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div @click="open = false" class="fixed inset-0 bg-black opacity-50"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Editar Categoría</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form :action="`/categorias/${categoriaId}`" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               x-model="nombre"
                               required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" 
                                  x-model="descripcion"
                                  rows="3"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            @click="open = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarCategoria(id, nombre, descripcion) {
    window.dispatchEvent(new CustomEvent('editar-categoria', {
        detail: { id, nombre, descripcion }
    }));
}
</script>
@endsection
