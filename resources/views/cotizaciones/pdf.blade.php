<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $cotizacion->codigo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #ff9900; /* Color corporativo Unimaq simulado */
        }
        .company-info {
            font-size: 12px;
            color: #666;
            text-align: right;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .info-box {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box th {
            text-align: left;
            width: 120px;
            padding: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .info-box td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: center;
        }
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .items-table td.desc {
            text-align: left;
        }
        .totals {
            width: 100%;
        }
        .totals table {
            width: 300px;
            float: right;
            border-collapse: collapse;
        }
        .totals th {
            text-align: right;
            padding: 5px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .totals td {
            text-align: right;
            padding: 5px;
            border: 1px solid #ddd;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            font-size: 11px;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .obs {
            margin-top: 20px;
            font-size: 13px;
            border-left: 4px solid #ff9900;
            padding-left: 10px;
            background-color: #fafafa;
            padding: 10px;
            width: 60%;
            float: left;
        }
        
        /* WATERMARK PARA RECHAZADAS Y EXPIRADAS */
        .watermark {
            position: absolute;
            top: 30%;
            left: 10%;
            font-size: 100px;
            color: rgba(255, 0, 0, 0.2);
            transform: rotate(-45deg);
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    @if(in_array($cotizacion->estado, ['Rechazada', 'Expirada']))
        <div class="watermark">{{ strtoupper($cotizacion->estado) }}</div>
    @endif

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo-text">UNIMAQ S.A.C.</div>
                    <div style="font-size: 12px; margin-top: 5px;">Venta y Alquiler de Maquinaria Pesada</div>
                </td>
                <td class="company-info">
                    RUC: 20100000001<br>
                    Av. Principal 123, Lima, Perú<br>
                    contacto@unimaq.com<br>
                    +51 1 555-1234
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Cotización {{ $cotizacion->codigo }}</div>

    <div class="info-box">
        <table>
            <tr>
                <th>Cliente:</th>
                <td>{{ $cotizacion->cliente->razon_social }}</td>
                <th>RUC/DNI:</th>
                <td>{{ $cotizacion->cliente->ruc_dni }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $cotizacion->cliente->email ?? 'N/A' }}</td>
                <th>Teléfono:</th>
                <td>{{ $cotizacion->cliente->telefono ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Vendedor:</th>
                <td>{{ $cotizacion->usuario->nombre }} {{ $cotizacion->usuario->apellido }}</td>
                <th>Estado:</th>
                <td style="font-weight: bold; color: {{ $cotizacion->estado == 'Aprobada' ? 'green' : ($cotizacion->estado == 'Rechazada' ? 'red' : 'black') }}">{{ $cotizacion->estado }}</td>
            </tr>
            <tr>
                <th>Fecha Emisión:</th>
                <td>{{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}</td>
                <th>Válido hasta:</th>
                <td>{{ \Carbon\Carbon::parse($cotizacion->fecha_vence)->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Ítem</th>
                <th class="desc">Descripción</th>
                <th>Cant.</th>
                <th>P.Unit (S/)</th>
                <th>Subtotal (S/)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacion->detalles as $index => $detalle)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="desc">{{ $detalle->descripcion }}</td>
                <td>{{ number_format($detalle->cantidad, 2) }}</td>
                <td>{{ number_format($detalle->precio_unit, 2) }}</td>
                <td>{{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; display: inline-block;">
        @if($cotizacion->observaciones)
        <div class="obs">
            <strong>Observaciones:</strong><br>
            {{ $cotizacion->observaciones }}
        </div>
        @endif

        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal:</th>
                    <td>S/ {{ number_format($cotizacion->monto_subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>IGV (18%):</th>
                    <td>S/ {{ number_format($cotizacion->igv, 2) }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td style="font-weight: bold; font-size: 16px;">S/ {{ number_format($cotizacion->monto_total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Este documento es una cotización comercial. Los precios están sujetos a variación sin previo aviso si la cotización ha expirado.<br>
        Documento generado el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
