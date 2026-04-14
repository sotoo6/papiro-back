<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numeroFactura }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2f2418;
            margin: 28px 34px;
            line-height: 1.45;
        }

        .page {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 28px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            border: 0;
            padding: 0;
        }

        .logo-box {
            width: 42%;
        }

        .logo {
            height: 52px;
        }

        .company-name {
            font-size: 30px;
            font-weight: bold;
            margin: 0 0 6px 0;
            color: #3b2a17;
        }

        .company-subtitle {
            font-size: 13px;
            color: #7a6853;
            margin-top: 4px;
        }

        .invoice-box {
            width: 58%;
            text-align: right;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #6b4423;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }

        .invoice-meta {
            margin-top: 10px;
            display: inline-block;
            text-align: left;
            background: #f7f1e8;
            border: 1px solid #d8c3a7;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .invoice-meta p {
            margin: 3px 0;
        }

        .label {
            font-weight: bold;
            color: #4a3420;
        }

        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #6b4423;
            border-bottom: 2px solid #d8c3a7;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .info-card {
            background: #fcfaf7;
            border: 1px solid #dfcfbb;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .info-card p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table {
            margin-top: 10px;
            border: 1px solid #d8c3a7;
        }

        .detail-table thead th {
            background: #efe4d4;
            color: #4a3420;
            font-size: 12px;
            font-weight: bold;
            padding: 10px 9px;
            border: 1px solid #d8c3a7;
            text-align: left;
        }

        .detail-table tbody td {
            border: 1px solid #e1d4c4;
            padding: 10px 9px;
            vertical-align: top;
        }

        .detail-table tbody tr:nth-child(even) {
            background: #faf6f1;
        }

        .totals-box {
            width: 320px;
            margin-left: auto;
            margin-top: 22px;
            border: 1px solid #d8c3a7;
            border-radius: 8px;
            background: #fcfaf7;
            padding: 14px 16px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            border: 0;
            padding: 5px 0;
        }

        .totals-table .total-row td {
            border-top: 1px solid #d8c3a7;
            padding-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #3b2a17;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 34px;
            padding-top: 12px;
            border-top: 1px solid #d8c3a7;
            text-align: center;
            color: #8a7864;
            font-size: 11px;
        }

        .muted {
            color: #7a6853;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo-box">
                        @if (file_exists(public_path('img/logo-papiro.png')))
                            <img src="{{ public_path('img/logo-papiro.png') }}" alt="Logo Papiro" class="logo">
                        @else
                            <p class="company-name">Papiro</p>
                        @endif

                        <p class="company-subtitle">Factura / Ticket de compra</p>
                    </td>

                    <td class="invoice-box">
                        <p class="invoice-title">FACTURA</p>

                        <div class="invoice-meta">
                            <p><span class="label">Número:</span> {{ $factura->numeroFactura }}</p>
                            <p><span class="label">Fecha:</span> {{ $factura->fechaEmision?->format('d/m/Y') }}</p>
                            <p><span class="label">Pedido:</span> #{{ $pedido->idPedido }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Datos del cliente</div>

            <div class="info-card">
                <p><span class="label">Nombre:</span> {{ $pedido->usuario->nombre }} {{ $pedido->usuario->apellidos }}</p>
                <p><span class="label">Correo:</span> {{ $pedido->usuario->email }}</p>
                <p><span class="label">Dirección de envío:</span> {{ $pedido->calleEnvio }}, {{ $pedido->numeroEnvio }}</p>
                <p>{{ $pedido->codigoPostalEnvio }} - {{ $pedido->ciudadEnvio }} ({{ $pedido->provinciaEnvio }})</p>
                <p>{{ $pedido->paisEnvio }}</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Detalle del pedido</div>

            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 38%;">Producto</th>
                        <th style="width: 12%;">Cantidad</th>
                        <th style="width: 18%;">Precio unitario</th>
                        <th style="width: 12%;">IVA</th>
                        <th style="width: 20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedido->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>{{ number_format($detalle->precioUnitario, 2, ',', '.') }} €</td>
                            <td>{{ number_format($detalle->ivaAplicado, 2, ',', '.') }} %</td>
                            <td>{{ number_format($detalle->subtotal, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">{{ number_format($pedido->totalPedido, 2, ',', '.') }} €</td>
                    </tr>
                    <tr>
                        <td>Descuento</td>
                        <td class="text-right">{{ number_format($pedido->descuento ?? 0, 2, ',', '.') }} €</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="text-right">{{ number_format($pedido->totalPedido, 2, ',', '.') }} €</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            Gracias por su compra en <strong>Papiro</strong>.
            <span class="muted">Documento generado automáticamente.</span>
        </div>
    </div>
</body>
</html>
