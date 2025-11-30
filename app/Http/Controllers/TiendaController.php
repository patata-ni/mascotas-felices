<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TiendaController extends Controller
{
    /**
     * Mostrar la tienda con productos
     */
    public function index(Request $request)
    {
        $query = Producto::where('activo', true)
                         ->where('stock_actual', '>', 0)
                         ->with('categoria');

        // Filtro por categoría
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Ordenamiento
        $orden = $request->get('orden', 'nombre');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio_venta', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_venta', 'desc');
                break;
            case 'nombre':
            default:
                $query->orderBy('nombre', 'asc');
                break;
        }

        $productos = $query->paginate(12);
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        return view('tienda.index', compact('productos', 'categorias'));
    }

    /**
     * Mostrar el carrito de compras
     */
    public function carrito()
    {
        return view('tienda.carrito');
    }

    /**
     * Procesar el checkout
     */
    public function checkout(Request $request)
    {
        // Si el cliente está logueado, usar sus datos
        if (session()->has('cliente_id')) {
            $validated = $request->validate([
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin',
                'descuento' => 'nullable|numeric|min:0',
            ]);

            $cliente = Cliente::findOrFail(session('cliente_id'));
        } else {
            // Cliente invitado
            $validated = $request->validate([
                'cliente.nombre' => 'required|max:150',
                'cliente.documento' => 'required|max:20',
                'cliente.tipo_documento' => 'required|in:INE,CURP,RFC,PASAPORTE',
                'cliente.telefono' => 'nullable|max:20',
                'cliente.email' => 'nullable|email|max:100',
                'cliente.direccion' => 'nullable',
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin',
                'descuento' => 'nullable|numeric|min:0',
            ]);

            // Buscar o crear cliente
            $cliente = Cliente::where('documento', $validated['cliente']['documento'])->first();
            
            if (!$cliente) {
                $cliente = Cliente::create([
                    'nombre' => $validated['cliente']['nombre'],
                    'documento' => $validated['cliente']['documento'],
                    'tipo_documento' => $validated['cliente']['tipo_documento'],
                    'telefono' => $validated['cliente']['telefono'] ?? null,
                    'email' => $validated['cliente']['email'] ?? null,
                    'direccion' => $validated['cliente']['direccion'] ?? null,
                    'puntos_fidelidad' => 0,
                    'total_compras' => 0,
                    'activo' => true,
                ]);
            }
        }

        DB::beginTransaction();

        try {

            // Calcular totales
            $subtotal = 0;
            $detalles = [];

            foreach ($validated['productos'] as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                
                // Verificar stock
                if ($producto->stock_actual < $item['cantidad']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para el producto: {$producto->nombre}. Disponible: {$producto->stock_actual}"
                    ], 400);
                }

                $precio = $producto->precio_venta;
                $cantidad = $item['cantidad'];
                $total_item = $precio * $cantidad;
                $subtotal += $total_item;

                $detalles[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $total_item
                ];
            }

            $descuento = $validated['descuento'] ?? 0;
            $impuesto = 0; // Sin IGV
            $total = $subtotal - $descuento;

            // Crear la venta
            $venta = new Venta();
            $venta->numero_venta = Venta::generarNumeroVenta();
            $venta->cliente_id = $cliente->id;
            $venta->usuario_id = Auth::id() ?? 1; // Usuario por defecto si no hay auth
            $venta->fecha_venta = now();
            $venta->subtotal = $subtotal;
            $venta->descuento = $descuento;
            $venta->impuesto = $impuesto;
            $venta->total = $total;
            $venta->metodo_pago = $validated['metodo_pago'];
            $venta->estado = 'completada';
            $venta->save();

            // Guardar detalles y actualizar stock
            foreach ($detalles as $detalle) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto']->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);

                // Actualizar stock
                $detalle['producto']->stock_actual -= $detalle['cantidad'];
                $detalle['producto']->save();
            }

            // Actualizar cliente
            $cliente->total_compras += $total;
            $cliente->puntos_fidelidad += intval($total / 100); // 1 punto por cada MX$100
            $cliente->save();

            // Actualizar sesión si el cliente está logueado
            if (session()->has('cliente_id')) {
                session(['cliente_puntos' => $cliente->puntos_fidelidad]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra realizada exitosamente',
                'venta_id' => $venta->id,
                'numero_venta' => $venta->numero_venta,
                'total' => $total,
                'puntos_ganados' => intval($total / 100)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la compra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar el comprobante de compra
     */
    public function comprobante($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto', 'usuario'])
                      ->findOrFail($id);

        return view('tienda.comprobante', compact('venta'));
    }

    /**
     * Mostrar formulario de login
     */
    public function login()
    {
        // Si ya está logueado, redirigir a la tienda
        if (session()->has('cliente_id')) {
            return redirect()->route('tienda.index');
        }

        return view('tienda.login');
    }

    /**
     * Procesar login de cliente
     */
    public function loginPost(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // Verificar si el email pertenece a un usuario del sistema (admin/staff)
        $usuario = \App\Models\User::where('email', $validated['email'])->first();
        
        if ($usuario && \Hash::check($validated['password'], $usuario->password)) {
            // Autenticar como usuario admin
            \Auth::login($usuario, $request->has('remember'));
            
            // Redirigir al dashboard
            return redirect()->route('dashboard')
                ->with('success', '¡Bienvenido, ' . $usuario->name . '!');
        }

        // Buscar cliente por email
        $cliente = Cliente::where('email', $validated['email'])
                         ->where('activo', true)
                         ->first();

        // Verificar si existe el cliente y la contraseña es correcta
        if (!$cliente || !\Hash::check($validated['password'], $cliente->contrasena)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Las credenciales son incorrectas']);
        }

        // Guardar en sesión
        session([
            'cliente_id' => $cliente->id,
            'cliente_nombre' => $cliente->nombre,
            'cliente_email' => $cliente->email,
            'cliente_documento' => $cliente->documento,
            'cliente_tipo_documento' => $cliente->tipo_documento,
            'cliente_puntos' => $cliente->puntos_fidelidad
        ]);

        // Actualizar remember token si se seleccionó recordar
        if ($request->has('remember')) {
            $cliente->update(['remember_token' => \Str::random(60)]);
        }

        return redirect()->route('tienda.index')
            ->with('success', '¡Bienvenido de nuevo, ' . $cliente->nombre . '!');
    }

    /**
     * Mostrar formulario de registro
     */
    public function registro()
    {
        // Si ya está logueado, redirigir a la tienda
        if (session()->has('cliente_id')) {
            return redirect()->route('tienda.index');
        }

        return view('tienda.registro');
    }

    /**
     * Procesar registro de cliente
     */
    public function registroPost(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'documento' => 'required|max:20|unique:clientes,documento',
            'tipo_documento' => 'required|in:INE,CURP,RFC,PASAPORTE',
            'telefono' => 'nullable|max:20',
            'email' => 'required|email|max:100|unique:clientes,email',
            'password' => 'required|min:6|confirmed',
            'direccion' => 'nullable',
            'fecha_nacimiento' => 'nullable|date',
            'acepta_terminos' => 'required|accepted',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'documento.required' => 'El número de documento es obligatorio',
            'documento.unique' => 'Este documento ya está registrado',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo electrónico válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'acepta_terminos.accepted' => 'Debes aceptar los términos y condiciones',
        ]);

        // Crear cliente
        $cliente = Cliente::create([
            'nombre' => $validated['nombre'],
            'documento' => $validated['documento'],
            'tipo_documento' => $validated['tipo_documento'],
            'telefono' => $validated['telefono'] ?? null,
            'email' => $validated['email'],
            'contrasena' => $validated['password'], // Se hasheará automáticamente por el cast
            'direccion' => $validated['direccion'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'puntos_fidelidad' => 0,
            'total_compras' => 0,
            'activo' => true,
        ]);

        // Login automático
        session([
            'cliente_id' => $cliente->id,
            'cliente_nombre' => $cliente->nombre,
            'cliente_email' => $cliente->email,
            'cliente_documento' => $cliente->documento,
            'cliente_tipo_documento' => $cliente->tipo_documento,
            'cliente_puntos' => $cliente->puntos_fidelidad
        ]);

        return redirect()->route('tienda.login')
            ->with('success', '¡Cuenta creada exitosamente! Por favor inicia sesión.');
    }

    /**
     * Cerrar sesión de cliente
     */
    public function logout()
    {
        session()->forget(['cliente_id', 'cliente_nombre', 'cliente_email', 'cliente_documento', 'cliente_tipo_documento', 'cliente_puntos']);
        
        return redirect()->route('tienda.index')
            ->with('success', 'Sesión cerrada exitosamente.');
    }

    /**
     * Ver perfil del cliente
     */
    public function perfil()
    {
        if (!session()->has('cliente_id')) {
            return redirect()->route('tienda.login')
                ->with('error', 'Debes iniciar sesión para ver tu perfil.');
        }

        $cliente = Cliente::with(['ventas' => function($query) {
            $query->orderBy('fecha_venta', 'desc')->take(10);
        }])->findOrFail(session('cliente_id'));

        return view('tienda.perfil', compact('cliente'));
    }
}
