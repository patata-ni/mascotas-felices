<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo Venta #{{ $venta->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .info-section {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .info-row strong {
            min-width: 80px;
        }
        
        .productos {
            margin-bottom: 15px;
        }
        
        .producto-item {
            margin-bottom: 8px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }
        
        .producto-nombre {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .producto-detalle {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        
        .totales {
            margin-top: 15px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .total-row.final {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px dashed #000;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #000;
            font-size: 10px;
        }
        
        .puntos-box {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 2px solid #000;
            background: #f5f5f5;
        }
        
        .puntos-box .numero {
            font-size: 20px;
            font-weight: bold;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <h1>MASCOTAS FELICES</h1>
        <p>RUC: 20123456789</p>
        <p>Av. Principal 123, Lima - Perú</p>
        <p>Tel: (01) 234-5678</p>
        <p>info@mascotasfelices.com</p>
    </div>

    <!-- Información de la Venta -->
    <div class="info-section">
        <div style="text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 10px;">
            BOLETA DE VENTA
        </div>
        <div class="info-row">
            <strong>Venta N°:</strong>
            <span>#{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <strong>Fecha:</strong>
            <span>{{ $venta->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <strong>Vendedor:</strong>
            <span>{{ $venta->usuario->name }}</span>
        </div>
        <div class="info-row">
            <strong>Pago:</strong>
            <span>{{ strtoupper($venta->metodo_pago) }}</span>
        </div>
    </div>

    <!-- Información del Cliente -->
    @if($venta->cliente)
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 5px;">CLIENTE:</div>
            <div class="info-row">
                <strong>Nombre:</strong>
                <span>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellidos }}</span>
            </div>
            <div class="info-row">
                <strong>DNI:</strong>
                <span>{{ $venta->cliente->dni }}</span>
            </div>
            <div class="info-row">
                <strong>Teléfono:</strong>
                <span>{{ $venta->cliente->telefono }}</span>
            </div>
        </div>
    @endif

    <!-- Productos -->
    <div class="productos">
        <div style="font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px;">
            PRODUCTOS
        </div>
        
        @foreach($venta->detalles as $detalle)
            <div class="producto-item">
                <div class="producto-nombre">{{ $detalle->producto->nombre }}</div>
                <div class="producto-detalle">
                    <span>{{ $detalle->cantidad }} x $ {{ number_format($detalle->precio_unitario, 2) }}</span>
                    <span>$ {{ number_format($detalle->subtotal, 2) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Totales -->
    <div class="totales">
        <div class="total-row">
            <span>SUBTOTAL:</span>
            <span>$ {{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="total-row final">
            <span>TOTAL:</span>
            <span>$ {{ number_format($venta->total, 2) }}</span>
        </div>
    </div>

    <!-- Puntos de Fidelidad -->
    @if($venta->cliente)
        <div class="puntos-box">
            <div style="font-size: 11px; margin-bottom: 5px;">PUNTOS GANADOS</div>
            <div class="numero">★ {{ $venta->puntos_ganados }}</div>
            <div style="font-size: 10px; margin-top: 5px;">Total acumulado: {{ $venta->cliente->puntos_fidelidad }} puntos</div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p style="font-weight: bold; margin-bottom: 5px;">¡GRACIAS POR SU COMPRA!</p>
        <p>www.mascotasfelices.com</p>
        <p style="margin-top: 10px;">
            @if($venta->estado == 'completada')
                Estado: COMPLETADA
            @else
                Estado: ANULADA
            @endif
        </p>
        <p style="margin-top: 5px; font-size: 9px;">
            Impreso el {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <!-- Botón de imprimir (solo en pantalla) -->
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; background: #4F46E5; color: white; border: none; border-radius: 5px;">
            <span style="font-family: Arial;">🖨️ Imprimir Recibo</span>
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; background: #6B7280; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            <span style="font-family: Arial;">✕ Cerrar</span>
        </button>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>

</body>
</html>
