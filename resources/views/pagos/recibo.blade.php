<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Recibo {{ $pago->codigo }}</title>
<style>
    :root{--cyan:#1fbfe6;--navy:#26313f;--muted:#8a96a3;--line:#e6ebf0}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Segoe UI',system-ui,Arial,sans-serif;background:#eef1f4;color:#39434f;padding:24px}
    .toolbar{max-width:720px;margin:0 auto 16px;display:flex;gap:10px;justify-content:flex-end}
    .btn{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border-radius:6px;font-size:14px;font-weight:600;border:0;cursor:pointer;text-decoration:none}
    .btn--primary{background:var(--cyan);color:#fff}
    .btn--light{background:#fff;color:var(--navy);border:1px solid var(--line)}
    .recibo{max-width:720px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 30px rgba(40,55,75,.12)}
    .recibo__head{background:linear-gradient(90deg,var(--cyan),#17a8cf);color:#fff;padding:26px 32px;display:flex;justify-content:space-between;align-items:flex-start;gap:20px}
    .recibo__head h1{font-size:22px;margin-bottom:2px}
    .recibo__head .sub{opacity:.9;font-size:13px;line-height:1.6}
    .recibo__num{text-align:right;font-size:13px;opacity:.95}
    .recibo__num b{display:block;font-size:18px;letter-spacing:.5px}
    .recibo__body{padding:30px 32px}
    .tag{display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
    .tag--pagado{background:#e3f7ee;color:#1f9c6c}
    .tag--pendiente{background:#fef2dd;color:#c9810f}
    .tag--anulado{background:#fde4e4;color:#d12f2f}
    .meta{display:grid;grid-template-columns:1fr 1fr;gap:8px 32px;margin:22px 0}
    .meta .row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--line)}
    .meta .row span:first-child{color:var(--muted)}
    .meta .row span:last-child{font-weight:600;color:var(--navy);text-align:right}
    table{width:100%;border-collapse:collapse;margin-top:6px}
    th{background:#f7f9fb;text-align:left;padding:12px 14px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--navy)}
    td{padding:14px;border-bottom:1px solid var(--line)}
    .total{display:flex;justify-content:flex-end;margin-top:18px}
    .total .box{background:var(--navy);color:#fff;border-radius:8px;padding:14px 26px;text-align:right;min-width:220px}
    .total .box small{opacity:.8;font-size:12px;text-transform:uppercase;letter-spacing:.5px}
    .total .box b{display:block;font-size:26px;margin-top:2px}
    .foot{margin-top:30px;display:flex;justify-content:space-between;align-items:flex-end;gap:20px}
    .firma{text-align:center;color:var(--muted);font-size:12px;flex:1}
    .firma .line{border-top:1px solid var(--navy);margin-top:48px;padding-top:6px}
    .gracias{text-align:center;color:var(--muted);font-size:12.5px;margin-top:26px;padding-top:18px;border-top:1px solid var(--line)}
    @media print{
        body{background:#fff;padding:0}
        .toolbar{display:none}
        .recibo{box-shadow:none;border-radius:0;max-width:100%}
    }
</style>
</head>
<body>
    @php $m = $academia->moneda ?? 'Bs'; @endphp
    <div class="toolbar">
        <a href="{{ route('pagos.index') }}" class="btn btn--light">← Volver</a>
        <button class="btn btn--primary" onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
    </div>

    <div class="recibo">
        <div class="recibo__head">
            <div>
                <h1>{{ $academia->nombre_academia ?? 'AcademiaPro' }}</h1>
                <div class="sub">
                    {{ $academia->direccion }}<br>
                    @if($academia->telefono){{ $academia->telefono }} · @endif{{ $academia->email }}
                    @if($academia->ruc_nit)<br>NIT/RUC: {{ $academia->ruc_nit }}@endif
                </div>
            </div>
            <div class="recibo__num">
                RECIBO DE PAGO
                <b>{{ $pago->codigo }}</b>
                {{ optional($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y') }}
            </div>
        </div>

        <div class="recibo__body">
            <span class="tag tag--{{ $pago->estado }}">{{ ucfirst($pago->estado) }}</span>

            <div class="meta">
                <div class="row"><span>Recibí de</span><span>{{ $pago->estudiante->nombre_completo ?? '—' }}</span></div>
                <div class="row"><span>Código estudiante</span><span>{{ $pago->estudiante->codigo ?? '—' }}</span></div>
                <div class="row"><span>Método de pago</span><span>{{ ucfirst($pago->metodo_pago) }}</span></div>
                <div class="row"><span>Curso</span><span>{{ $pago->matricula->curso->nombre ?? '—' }}</span></div>
                @if($pago->referencia)<div class="row"><span>Referencia</span><span>{{ $pago->referencia }}</span></div>@endif
                <div class="row"><span>Fecha</span><span>{{ optional($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y') }}</span></div>
            </div>

            <table>
                <thead><tr><th>Concepto</th><th style="text-align:right">Importe</th></tr></thead>
                <tbody>
                    <tr><td>{{ $pago->concepto }}</td><td style="text-align:right">{{ $m }} {{ number_format($pago->monto,2) }}</td></tr>
                </tbody>
            </table>

            <div class="total">
                <div class="box"><small>Total {{ $pago->estado=='pagado' ? 'pagado' : 'a pagar' }}</small><b>{{ $m }} {{ number_format($pago->monto,2) }}</b></div>
            </div>

            <div class="foot">
                <div class="firma"><div class="line">Recibí conforme</div></div>
                <div class="firma"><div class="line">{{ $academia->nombre_academia ?? 'AcademiaPro' }}</div></div>
            </div>

            <div class="gracias">¡Gracias por su pago! · {{ $academia->sitio_web }}</div>
        </div>
    </div>
</body>
</html>
