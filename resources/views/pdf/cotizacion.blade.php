<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $cotizacion->codigo }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .company-info { float: left; width: 50%; }
        .doc-info { float: right; width: 50%; text-align: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="company-info">
            <h2>UNIMAQ S.A.C.</h2>
            <p>Venta y Alquiler de Maquinaria Pesada<br>
            Lima, Perú</p>
        </div>
        <div class="doc-info">
            <h1>COTIZACIÓN</h1>
            <h3>{{ $cotizacion->codigo }}</h3>
        </div>
    </div>

    <div class="clearfix" style="margin-bottom: 20px;">
        <div style="float: left; width: 50%;">
            <strong>Cliente:</strong> {{ $cotizacion->cliente->razon_social ?? 'S/N' }}<br>
            <strong>RUC/DNI:</strong> {{ $cotizacion->cliente->ruc_dni ?? '' }}<br>
            <strong>Dirección:</strong> {{ $cotizacion->cliente->direccion ?? '-' }}<br>
            <strong>Email:</strong> {{ $cotizacion->cliente->email ?? '-' }}
        </div>
        <div style="float: right; width: 50%; text-align: right;">
            <strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}<br>
            <strong>Validez Hasta:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_vence)->format('d/m/Y') }}<br>
            <strong>Atención:</strong> {{ $cotizacion->usuario->nombre ?? '' }} {{ $cotizacion->usuario->apellido ?? '' }}<br>
            <strong>Estado:</strong> {{ $cotizacion->estado }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unit. (S/)</th>
                <th>Subtotal (S/)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacion->detalles as $idx => $detalle)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $detalle->descripcion }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ number_format($detalle->precio_unit, 2) }}</td>
                <td>{{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 40%; margin-left: auto;">
        <tr>
            <th>Subtotal:</th>
            <td class="text-right">S/ {{ number_format($cotizacion->monto_subtotal, 2) }}</td>
        </tr>
        <tr>
            <th>IGV (18%):</th>
            <td class="text-right">S/ {{ number_format($cotizacion->igv, 2) }}</td>
        </tr>
        <tr>
            <th>TOTAL:</th>
            <td class="text-right"><strong>S/ {{ number_format($cotizacion->monto_total, 2) }}</strong></td>
        </tr>
    </table>

    @if($cotizacion->observaciones)
    <div style="margin-top: 30px;">
        <strong>Observaciones:</strong>
        <p>{{ $cotizacion->observaciones }}</p>
    </div>
    @endif

    <div style="margin-top: 50px; text-align: center; color: #666; font-size: 10px;">
        <p>Este documento es una cotización y no tiene validez fiscal.</p>
        <p>Generado por el Sistema CRM - UNIMAQ S.A.C.</p>
    </div>
</body>
</html>
