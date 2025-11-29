{{-- ARCHIVO COMENTADO - NO SE EJECUTA --}}

{{--
@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Reportes</h1>
    <p class="text-gray-600 mt-2">Sistema de reportes y estadísticas del negocio</p>
</div>

<!-- Tarjetas de Acceso a Reportes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- Reporte de Ventas -->
    <a href="{{ route('reportes.ventas') }}" 
       class="bg-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Reporte de Ventas</h3>
        <p class="text-[#F3E8FF] text-sm">Análisis detallado de ventas por período, métodos de pago y tendencias</p>
    </a>

    <!-- Reporte de Inventario -->
    <a href="{{ route('reportes.inventario') }}" 
       class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-boxes text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Reporte de Inventario</h3>
        <p class="text-blue-100 text-sm">Estado actual del inventario, stock crítico y valorización</p>
    </a>

    <!-- Productos Más Vendidos -->
    <a href="{{ route('reportes.productos-mas-vendidos') }}" 
       class="bg-[#8B7AB8] rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Productos Top</h3>
        <p class="text-[#F3E8FF] text-sm">Ranking de productos más vendidos y rentables</p>
    </a>

    <!-- Reporte de Clientes -->
    <a href="{{ route('reportes.clientes') }}" 
       class="bg-[#E89A7B] rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Reporte de Clientes</h3>
        <p class="text-[#FFF5F0] text-sm">Análisis de clientes, compras y programa de fidelización</p>
    </a>

    <!-- Reporte de Pedidos -->
    <a href="{{ route('reportes.pedidos') }}" 
       class="bg-red-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck-loading text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Reporte de Pedidos</h3>
        <p class="text-red-100 text-sm">Estado de pedidos a proveedores y compras realizadas</p>
    </a>

    <!-- Reporte Financiero -->
    <a href="{{ route('reportes.financiero') }}" 
       class="bg-[#190C7B] rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-3xl"></i>
            </div>
            <i class="fas fa-arrow-right text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Reporte Financiero</h3>
        <p class="text-[#F3E8FF] text-sm">Resumen financiero, utilidades y márgenes de ganancia</p>
    </a>
</div>

<!-- Estadísticas Rápidas -->
<div class="mt-8 bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">
        <i class="fas fa-chart-pie text-[#190C7B] mr-2"></i>
        Resumen Ejecutivo
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#F5F3FF] rounded-lg p-4">
            <p class="text-sm text-green-700 font-medium mb-1">Ventas del Mes</p>
            <p class="text-2xl font-bold text-green-700">$ {{ number_format($ventasMes ?? 0, 2) }}</p>
            <p class="text-xs text-[#5B8FCC] mt-1">
                <i class="fas fa-arrow-up mr-1"></i>
                +15% vs mes anterior
            </p>
        </div>

        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-[#190C7B] font-medium mb-1">Productos en Stock</p>
            <p class="text-2xl font-bold text-[#190C7B]">{{ $productosStock ?? 0 }}</p>
            <p class="text-xs text-[#5B8FCC] mt-1">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $stockBajo ?? 0 }} con stock bajo
            </p>
        </div>

        <div class="bg-[#F5F3FF] rounded-lg p-4">
            <p class="text-sm text-[#9B0C76] font-medium mb-1">Clientes Activos</p>
            <p class="text-2xl font-bold text-[#9B0C76]">{{ $clientesActivos ?? 0 }}</p>
            <p class="text-xs text-[#8B7AB8] mt-1">
                <i class="fas fa-user-plus mr-1"></i>
                {{ $clientesNuevos ?? 0 }} nuevos este mes
            </p>
        </div>

        <div class="bg-[#FFF5F0] rounded-lg p-4">
            <p class="text-sm text-[#E89A7B] font-medium mb-1">Pedidos Pendientes</p>
            <p class="text-2xl font-bold text-[#E89A7B]">{{ $pedidosPendientes ?? 0 }}</p>
            <p class="text-xs text-[#E89A7B] mt-1">
                <i class="fas fa-clock mr-1"></i>
                Por valor de $ {{ number_format($montoPendiente ?? 0, 2) }}
            </p>
        </div>
    </div>
</div>
@endsection
--}}
