<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Factura {{ $factura->numero }}</title>
<style>
    :root{--violet:#6c5ce7;--navy:#26313f;--muted:#8a96a3;--line:#e6ebf0}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Segoe UI',Arial,sans-serif;background:#eef1f4;color:#39434f;padding:24px}
    .toolbar{max-width:720px;margin:0 auto 16px;display:flex;gap:10px;justify-content:flex-end}
    .btn{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border-radius:6px;font-size:14px;font-weight:600;border:0;cursor:pointer;text-decoration:none}
    .btn--v{background:var(--violet);color:#fff}.btn--l{background:#fff;color:var(--navy);border:1px solid var(--line)}
    .doc{max-width:720px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 30px rgba(40,55,75,.12)}
    .doc__head{background:linear-gradient(90deg,#6c5ce7,#4834b5);color:#fff;padding:26px 32px;display:flex;justify-content:space-between;gap:20px}
    .doc__head h1{font-size:22px}.doc__head .sub{opacity:.9;font-size:13px;line-height:1.6}
    .num{text-align:right;font-size:13px}.num b{display:block;font-size:18px}
    .body{padding:30px 32px}
    .tag{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase}
    .tag.pagada{background:#e3f7ee;color:#1f9c6c}.tag.pendiente{background:#fef2dd;color:#c9810f}.tag.anulada{background:#fde4e4;color:#d12f2f}
    .meta{display:grid;grid-template-columns:1fr 1fr;gap:8px 32px;margin:22px 0}
    .meta .row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--line)}
    .meta .row span:first-child{color:var(--muted)}.meta .row span:last-child{font-weight:600;color:var(--navy)}
    table{width:100%;border-collapse:collapse;margin-top:6px}
    th{background:#f7f9fb;text-align:left;padding:12px 14px;font-size:12px;text-transform:uppercase;color:var(--navy)}
    td{padding:14px;border-bottom:1px solid var(--line)}
    .total{display:flex;justify-content:flex-end;margin-top:18px}
    .total .box{background:var(--navy);color:#fff;border-radius:8px;padding:14px 26px;text-align:right;min-width:220px}
    .total .box small{opacity:.8;font-size:12px;text-transform:uppercase}.total .box b{display:block;font-size:26px}
    .gracias{text-align:center;color:var(--muted);font-size:12.5px;margin-top:26px;padding-top:18px;border-top:1px solid var(--line)}
    @media print{body{background:#fff;padding:0}.toolbar{display:none}.doc{box-shadow:none;border-radius:0;max-width:100%}}
</style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('superadmin.facturas.index') }}" class="btn btn--l">← Volver</a>
        <button class="btn btn--v" onclick="window.print()">🖨 Imprimir / PDF</button>
    </div>
    <div class="doc">
        <div class="doc__head">
            <div><h1>AcademiaPro SaaS</h1><div class="sub">Plataforma de gestión académica<br>facturacion@academiapro.com</div></div>
            <div class="num">FACTURA<b>{{ $factura->numero }}</b>{{ optional($factura->fecha_emision)->format('d/m/Y') }}</div>
        </div>
        <div class="body">
            <span class="tag {{ $factura->estado }}">{{ ucfirst($factura->estado) }}</span>
            <div class="meta">
                <div class="row"><span>Cliente</span><span>{{ $factura->academia->nombre_academia ?? '—' }}</span></div>
                <div class="row"><span>NIT/RUC</span><span>{{ $factura->academia->ruc_nit ?? '—' }}</span></div>
                <div class="row"><span>Periodo</span><span>{{ $factura->periodo }}</span></div>
                <div class="row"><span>Plan</span><span>{{ $factura->suscripcion->plan->nombre ?? $factura->academia->plan->nombre ?? '—' }}</span></div>
                @if($factura->fecha_pago)<div class="row"><span>Fecha de pago</span><span>{{ $factura->fecha_pago->format('d/m/Y') }}</span></div>@endif
                @if($factura->metodo_pago)<div class="row"><span>Método</span><span>{{ ucfirst($factura->metodo_pago) }}</span></div>@endif
            </div>
            <table>
                <thead><tr><th>Concepto</th><th style="text-align:right">Importe</th></tr></thead>
                <tbody><tr><td>Suscripción {{ $factura->suscripcion->plan->nombre ?? '' }} · {{ $factura->periodo }}</td><td style="text-align:right">Bs {{ number_format($factura->monto,2) }}</td></tr></tbody>
            </table>
            <div class="total"><div class="box"><small>Total</small><b>Bs {{ number_format($factura->monto,2) }}</b></div></div>
            <div class="gracias">Gracias por confiar en AcademiaPro SaaS.</div>
        </div>
    </div>
</body>
</html>
