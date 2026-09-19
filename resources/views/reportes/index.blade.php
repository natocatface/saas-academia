@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
@php $m = $academia->moneda ?? 'Bs'; @endphp
<div class="page-head"><div><h1>Reportes y estadísticas</h1><p>Análisis financiero, académico y de asistencia</p></div>
<a href="{{ route('reportes.imprimir') }}" target="_blank" class="btn btn--primary">@include('layouts.icons',['i'=>'print']) Imprimir / PDF</a></div>

{{-- Tarjetas resumen --}}
<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'users'])</div><div><div class="stat__num">{{ number_format($totales['estudiantes']) }}</div><div class="stat__label">Estudiantes</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'clipboard'])</div><div><div class="stat__num">{{ number_format($totales['matriculas']) }}</div><div class="stat__label">Matrículas activas</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">{{ $m }} {{ number_format($totales['ingreso'],0) }}</div><div class="stat__label">Ingresos totales</div></div></div>
    <div class="card stat"><div class="stat__icon bg-orange">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num">{{ $m }} {{ number_format($totales['morosidad'],0) }}</div><div class="stat__label">Por cobrar</div></div></div>
</div>

{{-- Ingresos por mes --}}
<div class="card" style="margin-bottom:20px">
    <div class="flex between center" style="margin-bottom:6px;flex-wrap:wrap;gap:10px">
        <h3 class="card__title mb-0">Ingresos · últimos 12 meses</h3>
        <a href="{{ route('reportes.exportar','ingresos') }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'download']) Exportar ingresos (CSV)</a>
    </div>
    <div class="chart-box"><canvas id="chartIngresos"></canvas></div>
</div>

{{-- Estado de cobranza + promedio por curso --}}
<div class="grid grid-2" style="grid-template-columns:1fr 1.6fr;margin-bottom:20px">
    <div class="card">
        <h3 class="card__title">Estado de cobranza</h3>
        <div class="chart-box chart-box--sm"><canvas id="chartPagos"></canvas></div>
    </div>
    <div class="card">
        <h3 class="card__title">Promedio de notas por curso</h3>
        <div class="chart-box chart-box--sm"><canvas id="chartProm"></canvas></div>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom:20px">
    {{-- Morosidad --}}
    <div class="card">
        <div class="flex between center" style="margin-bottom:10px;flex-wrap:wrap;gap:8px">
            <h3 class="card__title mb-0">Morosidad (top deudores)</h3>
            <a href="{{ route('reportes.exportar','morosidad') }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'download']) CSV</a>
        </div>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Estudiante</th><th>Cuotas</th><th style="text-align:right">Deuda</th></tr></thead>
            <tbody>
            @forelse($morosos as $mo)
                <tr><td>{{ $mo->estudiante->nombre_completo ?? '—' }}</td><td>{{ $mo->cuotas }}</td>
                    <td style="text-align:right"><b>{{ $m }} {{ number_format($mo->deuda,2) }}</b></td></tr>
            @empty<tr><td colspan="3" class="empty">Sin pagos pendientes 🎉</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>

    {{-- Asistencia por curso --}}
    <div class="card">
        <div class="flex between center" style="margin-bottom:10px;flex-wrap:wrap;gap:8px">
            <h3 class="card__title mb-0">Tasa de asistencia por curso</h3>
            <a href="{{ route('reportes.exportar','asistencia') }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'download']) CSV</a>
        </div>
        @forelse($asistenciaCursos as $a)
            <div class="prog">
                <div class="prog__label"><span>{{ $a->curso }}</span><span>{{ $a->tasa }}%</span></div>
                <div class="prog__bar"><div class="prog__fill" data-w="{{ $a->tasa }}" style="width:0;background:{{ $a->tasa>=80?'var(--green)':($a->tasa>=60?'var(--yellow)':'var(--orange)') }}"></div></div>
            </div>
        @empty<p class="empty">Sin registros de asistencia.</p>@endforelse
    </div>
</div>

{{-- Rendimiento académico --}}
<div class="card">
    <div class="flex between center" style="margin-bottom:10px;flex-wrap:wrap;gap:8px">
        <h3 class="card__title mb-0">Rendimiento académico por curso</h3>
        <a href="{{ route('reportes.exportar','rendimiento') }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'download']) Exportar notas (CSV)</a>
    </div>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Curso</th><th>Evaluaciones</th><th>Promedio</th><th>% Aprobación</th></tr></thead>
        <tbody>
        @forelse($rendimiento as $r)
            <tr>
                <td>{{ $r->curso }}</td>
                <td>{{ $r->evaluaciones }}</td>
                <td><span class="badge badge--{{ $r->promedio>=51?'green':'red' }}">{{ number_format($r->promedio,1) }}</span></td>
                <td>{{ $r->aprobacion }}%</td>
            </tr>
        @empty<tr><td colspan="4" class="empty">Sin calificaciones registradas.</td></tr>@endforelse
        </tbody>
    </table></div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartIngresos'), {
    type: 'bar',
    data: { labels: @json($mesesLbl),
        datasets: [{ label: 'Ingresos ({{ $m }})', data: @json($ingresosMes), backgroundColor: '#1fbfe6', borderRadius: 4 }] },
    options: { responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: '#eef1f4' } }, x: { grid: { display: false } } } }
});

new Chart(document.getElementById('chartPagos'), {
    type: 'doughnut',
    data: { labels: ['Cobrado','Pendiente'],
        datasets: [{ data: [{{ $pagosEstado['pagado'] }}, {{ $pagosEstado['pendiente'] }}], backgroundColor: ['#2fc28a','#fbb13c'], borderWidth: 0 }] },
    options: { responsive: true, maintainAspectRatio: false, cutout: '62%',
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } } }
});

new Chart(document.getElementById('chartProm'), {
    type: 'bar',
    data: { labels: @json($rendimiento->pluck('curso')),
        datasets: [{ label: 'Promedio', data: @json($rendimiento->pluck('promedio')),
            backgroundColor: @json($rendimiento->map(fn($r) => $r->promedio >= 51 ? '#2fc28a' : '#f04e4e')), borderRadius: 4 }] },
    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, max: 100, grid: { color: '#eef1f4' } }, y: { grid: { display: false } } } }
});
</script>
@endpush
