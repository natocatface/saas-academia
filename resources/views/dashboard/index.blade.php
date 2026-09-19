@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h1>Resumen General</h1>
        <p>Bienvenido de nuevo, {{ auth()->user()->name }} · {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}</p>
    </div>
    <a href="{{ route('matriculas.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nueva matrícula</a>
</div>

{{-- ===== Tarjetas de estadísticas ===== --}}
<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat">
        <div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'users'])</div>
        <div><div class="stat__num">{{ number_format($totalEstudiantes) }}</div><div class="stat__label">Estudiantes</div></div>
    </div>
    <div class="card stat">
        <div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'book'])</div>
        <div><div class="stat__num">{{ number_format($totalCursos) }}</div><div class="stat__label">Cursos activos</div></div>
    </div>
    <div class="card stat">
        <div class="stat__icon bg-green">@include('layouts.icons',['i'=>'teacher'])</div>
        <div><div class="stat__num">{{ number_format($totalDocentes) }}</div><div class="stat__label">Docentes</div></div>
    </div>
    <div class="card stat">
        <div class="stat__icon bg-orange">@include('layouts.icons',['i'=>'money'])</div>
        <div><div class="stat__num">Bs {{ number_format($ingresosMes, 0) }}</div><div class="stat__label">Ingresos del mes</div></div>
    </div>
</div>

{{-- ===== Uso del plan ===== --}}
@if($academia->plan ?? false)
@php $rec = [['estudiantes','Estudiantes','var(--blue)'],['usuarios','Usuarios','var(--green)'],['cursos','Cursos','var(--yellow)']]; @endphp
<div class="card" style="margin-bottom:20px">
    <div class="flex between center" style="margin-bottom:10px;flex-wrap:wrap;gap:8px">
        <h3 class="card__title mb-0">Uso de tu plan · {{ $academia->plan->nombre }}</h3>
        @if(auth()->user()->esAdmin())<a href="{{ route('miplan.index') }}" class="btn btn--light btn--sm">Ver mi plan</a>@endif
    </div>
    <div class="grid grid-3">
        @foreach($rec as $r)
            @php $lim = \App\Support\LimitePlan::limite($r[0]); $uso = \App\Support\LimitePlan::uso($r[0]); $pct = \App\Support\LimitePlan::porcentaje($r[0]); @endphp
            <div class="prog">
                <div class="prog__label"><span>{{ $r[1] }}</span><span>{{ $uso }} / {{ $lim>0 ? $lim : '∞' }}</span></div>
                <div class="prog__bar"><div class="prog__fill" data-w="{{ $lim>0 ? $pct : 4 }}" style="width:0;background:{{ $pct>=90 ? 'var(--red)' : $r[2] }}"></div></div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== Gráfico de barras + lista de datos ===== --}}
<div class="grid grid-2" style="grid-template-columns:1.6fr 1fr;margin-bottom:20px">
    <div class="card">
        <div class="flex between center"><h3 class="card__title mb-0">Ingresos y Matrículas · últimos 6 meses</h3></div>
        <div class="chart-box"><canvas id="chartIngresos"></canvas></div>
    </div>
    <div class="card">
        <h3 class="card__title">Indicadores clave</h3>
        <div class="dl" style="grid-template-columns:1fr">
            <div class="dl__row"><span>Matrículas activas</span><span>{{ number_format($matriculasActivas) }}</span></div>
            <div class="dl__row"><span>Pendiente de cobro</span><span>Bs {{ number_format($pendientesCobro,0) }}</span></div>
            <div class="dl__row"><span>Tasa de asistencia</span><span>{{ $tasaAsistencia }}%</span></div>
            <div class="dl__row"><span>Tasa de cobranza</span><span>{{ $tasaCobro }}%</span></div>
            <div class="dl__row"><span>Ocupación de cupos</span><span>{{ $capacidad }}%</span></div>
            <div class="dl__row"><span>Docentes activos</span><span>{{ number_format($totalDocentes) }}</span></div>
        </div>
    </div>
</div>

{{-- ===== Barras de progreso + anillos ===== --}}
<div class="grid grid-2" style="margin-bottom:20px">
    <div class="card">
        <h3 class="card__title">Estudiantes por curso (top 5)</h3>
        @php $colores=['var(--blue)','var(--yellow)','var(--orange)','var(--green)','var(--navy)']; $max=max(1,optional($porCurso->first())->matriculas_count ?? 1); @endphp
        @forelse($porCurso as $idx => $c)
            <div class="prog">
                <div class="prog__label"><span>{{ $c->nombre }}</span><span>{{ $c->matriculas_count }}</span></div>
                <div class="prog__bar"><div class="prog__fill" data-w="{{ round($c->matriculas_count/$max*100) }}" style="width:0;background:{{ $colores[$idx % 5] }}"></div></div>
            </div>
        @empty
            <p class="empty">Aún no hay matrículas registradas.</p>
        @endforelse
    </div>
    <div class="card">
        <h3 class="card__title">Rendimiento del periodo</h3>
        <div class="rings">
            @php
                $ringData = [
                    ['Asistencia', $tasaAsistencia, 'var(--blue)'],
                    ['Cobranza', $tasaCobro, 'var(--orange)'],
                    ['Ocupación', $capacidad, 'var(--navy)'],
                ];
            @endphp
            @foreach($ringData as $r)
                @php $circ=2*3.1416*42; $off=$circ-($r[1]/100*$circ); @endphp
                <div>
                    <div class="ring">
                        <svg width="96" height="96" viewBox="0 0 96 96">
                            <circle cx="48" cy="48" r="42" fill="none" stroke="var(--line)" stroke-width="8"/>
                            <circle cx="48" cy="48" r="42" fill="none" stroke="{{ $r[2] }}" stroke-width="8"
                                    stroke-linecap="round" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $off }}"/>
                        </svg>
                        <div class="ring__val">{{ $r[1] }}%</div>
                    </div>
                    <div class="ring__lbl">{{ $r[0] }}</div>
                </div>
            @endforeach
        </div>
        <div class="chart-box chart-box--sm" style="margin-top:10px"><canvas id="chartArea"></canvas></div>
    </div>
</div>

{{-- ===== Últimas matrículas ===== --}}
<div class="card">
    <div class="flex between center" style="margin-bottom:10px">
        <h3 class="card__title mb-0">Últimas matrículas</h3>
        <a href="{{ route('matriculas.index') }}" class="btn btn--light btn--sm">Ver todas</a>
    </div>
    <div class="table-wrap">
        <table class="tbl">
            <thead><tr><th>Estudiante</th><th>Curso</th><th>Periodo</th><th>Fecha</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($ultimas as $m)
                <tr>
                    <td>{{ $m->estudiante->nombre_completo ?? '—' }}</td>
                    <td>{{ $m->curso->nombre ?? '—' }}</td>
                    <td>{{ $m->periodo }}</td>
                    <td>{{ optional($m->created_at)->format('d/m/Y') }}</td>
                    <td>
                        @php $b=['activa'=>'green','retirada'=>'red','finalizada'=>'blue','suspendida'=>'yellow'][$m->estado] ?? 'gray'; @endphp
                        <span class="badge badge--{{ $b }}">{{ ucfirst($m->estado) }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">No hay matrículas recientes.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const meses = @json($meses);
const ingresos = @json($ingresos);
const matriculas = @json($matriculasMes);

new Chart(document.getElementById('chartIngresos'), {
    type: 'bar',
    data: {
        labels: meses,
        datasets: [
            { label: 'Ingresos (Bs)', data: ingresos, backgroundColor: '#1fbfe6', borderRadius: 4, yAxisID: 'y' },
            { label: 'Matrículas', type: 'line', data: matriculas, borderColor: '#f4663b', backgroundColor: '#f4663b',
              tension: .35, yAxisID: 'y1', pointRadius: 4, pointBackgroundColor: '#f4663b' }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#eef1f4' } },
            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('chartArea'), {
    type: 'line',
    data: { labels: meses, datasets: [{ label: 'Matrículas', data: matriculas, fill: true,
        backgroundColor: 'rgba(31,191,230,.15)', borderColor: '#1fbfe6', tension: .4, pointRadius: 0 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
        scales: { y: { display: false, beginAtZero: true }, x: { grid: { display: false } } } }
});
</script>
@endpush
