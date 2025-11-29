<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login de usuario y generación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        if (!$user->activo) {
            return response()->json([
                'message' => 'Usuario inactivo'
            ], 403);
        }

        // Crear token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Registro de nuevo usuario
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|max:20'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'role' => 'vendedor', // rol por defecto
            'activo' => true,
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Logout - eliminar token actual
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * Estadísticas para el dashboard
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'ventas_hoy' => \App\Models\Venta::whereDate('fecha_venta', today())
                ->where('estado', 'completada')
                ->sum('total'),
            'cantidad_ventas_hoy' => \App\Models\Venta::whereDate('fecha_venta', today())
                ->where('estado', 'completada')
                ->count(),
            'productos_stock_bajo' => \App\Models\Producto::whereRaw('stock_actual <= stock_minimo')->count(),
            'clientes_activos' => \App\Models\Cliente::where('activo', true)->count(),
        ];

        if ($user->esAdministrador() || $user->esInventario()) {
            $stats['pedidos_pendientes'] = \App\Models\Pedido::whereIn('estado', ['pendiente', 'confirmado', 'enviado'])->count();
        }

        return response()->json($stats);
    }

    /**
     * Sincronización de datos offline
     */
    public function sync(Request $request)
    {
        // Aquí se implementaría la lógica de sincronización
        // Por ahora retornamos un mensaje de éxito
        return response()->json([
            'message' => 'Datos sincronizados correctamente',
            'timestamp' => now()
        ]);
    }
}
