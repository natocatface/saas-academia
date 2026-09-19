<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reporte general · {{ $academia->nombre_academia ?? 'AcademiaPro' }}</title>
<style>
    :root{--cyan:#1fbfe6;--navy:#26313f;--muted:#8a96a3;--line:#e6ebf0}
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Segoe UI',Arial,sans-serif;color:#39434f;background:#eef1f4;padding:24px}
    .bar{max-width:820px;margin:0 auto 14px;display:flex;gap:10px;justify-content:flex-end}
    .btn{padding:10px 18px;border-radius:6px;font-size:14px;font-weight:600;border:0;cursor:pointer;text-decoration:none}
    .btn--c{background:var(--cyan);color:#fff}.btn--l{background:#fff;color:var(--navy);border:1px solid var(--line)}
    .sheet{max-width:820px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 30px rgba(40,55,75,.12)}
    .head{background:linear-gradient(90deg,var(--cyan),#17a8cf);color:#fff;padding:24px 30px;display:flex;justify-content:space-between;gap:16px}
    .head h1{font-size:20px}.head .s{font-size:12.5px;opacity:.9;line-height:1.5}
    .head .r{text-align:right;font-size:12.5px}
    .body{padding:26px 30px}
    h2{font-size:14px;text-transform:uppercase;letter-spacing:.6px;color:var(--navy);margin:24px 0 10px;border-bottom:2px solid var(--cyan);padding-bottom:6px}
    h2:first-child{margin-top:0}
    .cards{display:flex;gap:14px;flex-wrap:wrap}
    .c{flex:1;min-width:130px;background:#f7f9fb;border-radius:8px;padding:14px}
    .c b{display:block;font-size:22px;color:var(--navy)}.c span{font-size:12px;color:var(--muted)}
    table{width:100%;border-collapse:collapse;font-size:13px;margin-top:4px}
    th{text-align:left;background:#f7f9fb;padding:9px 12px;color:var(--navy);font-size:11.5px;text-transform:uppercase}
    td{padding:9px 12px;border-bottom:1px solid var(--line)}
    .foot{text-align:center;color:var(--muted);font-size:12px;margin-top:24px;padding-top:14px;border-top:1px solid var(--line)}
    @media print{body{background:#fff;padding:0}.bar{display:none}.sheet{box-shadow:none;border-radius:0;max-width:100%}}
</style>
</head>
<body>
    <div class="bar">
        <a href="{{ route('reportes.index') }}" class="btn btn--l">← Volver</a>
        <button class="btn btn--c" onclick="window.print()">🖨 Imprimir / Guardar PDF</button>
    </div>
    <div class="sheet">
        <div class="head">
            <div><h1>{{ $academia->nombre_academia ?? 'AcademiaPro' }}</h1>
                <div class="s">{{ $academia->direccion ?? '' }}<br>{{ $academia->telefono ?? '' }} · {{ $academia->email ?? '' }}</div></div>
            <div class="r">REPORTE GENERAL<br><b>{{ now()->format('d/m/Y H:i') }}</b><br>Periodo {{ $academia->periodo_actual ?? date('Y') }}</div>
        </div>
        <div class="body">
            <h2>Resumen</h2>
            <div class="cards">
                <div class="c"><b>{{ number_format($totales['estudiantes']) }}</b><span>Estudiantes</span></div>
                <div class="c"><b>{{ number_format($totales['matriculas']) }}</b><span>Matrículas activas</span></div>
                <div class="c"><b>Bs {{ number_format($totales['ingreso'],0) }}</b><span>Ingresos</span></div>
                <div class="c"><b>Bs {{ number_format($totales['morosidad'],0) }}</b><span>Por cobrar</span></div>
            </div>

            <h2>Morosidad (top deudores)</h2>
            <table><thead><tr><th>Estudiante</th><th>Cuotas</th><th style="text-align:right">Deuda</th></tr></thead><tbody>
            @forelse($morosos as $m)<tr><td>{{ $m->estudiante->nombre_completo ?? '—' }}</td><td>{{ $m->cuotas }}</td><td style="text-align:right">Bs {{ number_format($m->deuda,2) }}</td></tr>
            @empty<tr><td colspan="3">Sin pagos pendientes.</td></tr>@endforelse
            </tbody></table>

            <h2>Rendimiento por curso</h2>
            <table><thead><tr><th>Curso</th><th>Evaluaciones</th><th>Promedio</th></tr></thead><tbody>
            @forelse($rendimiento as $r)<tr><td>{{ $r->curso }}</td><td>{{ $r->evaluaciones }}</td><td>{{ number_format($r->promedio,1) }}</td></tr>
            @empty<tr><td colspan="3">Sin calificaciones.</td></tr>@endforelse
            </tbody></table>

            <div class="foot">Generado por AcademiaPro · {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</body>
</html>
