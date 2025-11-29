@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Proveedores</h1>
            <p class="text-gray-600 mt-1">Gestión de proveedores y empresas</p>
        </div>
        @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
            <a href="{{ route('proveedores.create') }}" 
               class="px-4 py-3 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E] transition shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Proveedor
            </a>
        @endif
    </div>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('proveedores.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <input type="text" 
                   name="buscar" 
                   value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre, RUC, email..." 
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        <div>
            <select name="activo" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#190C7B] text-white rounded-lg hover:bg-[#2D1B9E]">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
            <a href="{{ route('proveedores.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Proveedores</p>
                <p class="text-3xl font-bold text-gray-800">{{ $proveedores->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-[#190C7B] text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Proveedores Activos</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">{{ $proveedores->where('activo', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-[#EDE9FE] rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-[#5B8FCC] text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Productos Suministrados</p>
                <p class="text-3xl font-bold text-[#5B8FCC]">
                    {{ $proveedores->sum(function($p) { return $p->productos->count(); }) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-[#5B8FCC] text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Productos</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($proveedores as $proveedor)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#EDE9FE] rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-truck text-[#190C7B]"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $proveedor->nombre }}</p>
                                    <p class="text-sm text-gray-500">{{ $proveedor->empresa }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            {{ $proveedor->ruc }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>{{ $proveedor->email }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>{{ $proveedor->telefono }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-[#190C7B] rounded-full text-sm font-medium">
                                {{ $proveedor->productos->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($proveedor->activo)
                                <span class="px-3 py-1 bg-[#EDE9FE] text-[#190C7B] rounded-full text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Activo
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('proveedores.show', $proveedor) }}" 
                                   class="text-[#5B8FCC] hover:text-[#190C7B]" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
                                    <a href="{{ route('proveedores.edit', $proveedor) }}" 
                                       class="text-[#190C7B] hover:text-[#2D1B9E]" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('proveedores.destroy', $proveedor) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Eliminar este proveedor?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-truck text-4xl mb-3 text-gray-300"></i>
                            <p>No hay proveedores registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proveedores->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $proveedores->links() }}
        </div>
    @endif
</div>
@endsection
