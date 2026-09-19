@extends('layouts.superadmin')
@section('title', 'Panel general')
@section('content')
<div class="page-head">
    <div><h1>Panel de la plataforma</h1><p>Visión global de todas las academias del SaaS</p></div>
    <a href="{{ route('superadmin.academias.create') }}" class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'plus']) Nueva academia</a>
</div>

<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon" style="background:linear-gradient(135deg,#6c5ce7,#4834b5)">@include('layouts.icons',['i'=>'teacher'])</div><div><div class="stat__num">{{ $totalAcademias }}</div><div class="stat__label">Academias</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'check'])</div><div><div class="stat__num">{{ $academiasActivas }}</div><div class="stat__label">Activas</div></div></div>
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'users'])</div><div><div class="stat__num">{{ number_format($totalEstudiantes) }}</div><div class="stat__label">Estudiantes (global)</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($mrr,0) }}</div><div class="stat__label">Ingreso mensual (MRR)</div></div></div>
</div>

<div class="grid grid-2" style="grid-template-columns:1.6fr 1fr;margin-bottom:20px">
    <div class="card">
        <h3 class="card__title">Nuevas academias · últimos 6 meses</h3>
        <div class="chart-box"><canvas id="chartAltas"></canvas></div>
    </div>
    <div class="card">
        <h3 class="card__title">Indicadores</h3>
        <div class="dl" style="grid-template-columns:1fr">
            <div class="dl__row"><span>En periodo de prueba</span><span>{{ $academiasPrueba }}</span></div>
            <div class="dl__row"><span>Usuarios totales</span><span>{{ number_format($totalUsuarios) }}</span></div>
            <div class="dl__row"><span>Ingresos acumulados</span><span>Bs {{ number_format($ingresoPlataforma,0) }}</span></div>
            <div class="dl__row"><span>MRR estimado</span><span>Bs {{ number_format($mrr,0) }}</span></div>
        </div>
        <h3 class="card__title" style="margin-top:18px">Academias por plan</h3>
        @foreach($porPlan as $pp)
            <div class="prog">
                <div class="prog__label"><span>{{ $pp->plan->nombre ?? 'Sin plan' }}</span><span>{{ $pp->total }}</span></div>
                <div class="prog__bar"><div class="prog__fill" data-w="{{ $totalAcademias>0?round($pp->total/$totalAcademias*100):0 }}" style="width:0;background:{{ $pp->plan->color ?? '#8a96a3' }}"></div></div>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="flex between center" style="margin-bottom:10px"><h3 class="card__title mb-0">Academias recientes</h3>
        <a href="{{ route('superadmin.academias.index') }}" class="btn btn--light btn--sm">Ver todas</a></div>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Academia</th><th>Plan</th><th>Estado</th><th>Registro</th><th></th></tr></thead>
        <tbody>
        @forelse($recientes as $a)
            <tr>
                <td><b>{{ $a->nombre_academia }}</b><br><small class="text-muted">{{ $a->email }}</small></td>
                <td>@if($a->plan)<span class="badge" style="background:{{ $a->plan->color }}22;color:{{ $a->plan->color }}">{{ $a->plan->nombre }}</span>@else <span class="text-muted">—</span>@endif</td>
                <td>@php $b=['activa'=>'green','prueba'=>'yellow','suspendida'=>'red'][$a->estado]??'gray'; @endphp<span class="badge badge--{{ $b }}">{{ ucfirst($a->estado) }}</span></td>
                <td>{{ optional($a->fecha_registro)->format('d/m/Y') }}</td>
                <td class="act"><a href="{{ route('superadmin.academias.show',$a) }}">@include('layouts.icons',['i'=>'eye'])</a></td>
            </tr>
        @empty<tr><td colspan="5" class="empty">Aún no hay academias.</td></tr>@endforelse
        </tbody>
    </table></div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartAltas'), {
    type: 'bar',
    data: { labels: @json($meses), datasets: [{ label: 'Nuevas academias', data: @json($altas), backgroundColor: '#6c5ce7', borderRadius: 4 }] },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}},
        scales:{ y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'#eef1f4'}}, x:{grid:{display:false}} } }
});
</script>
@endpush
