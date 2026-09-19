@extends('layouts.portal')
@section('title', 'Mi portal')
@section('content')
@php $m = $academia->moneda ?? 'Bs'; @endphp
<div class="portal__hi">
    <div class="av">{{ auth()->user()->iniciales() }}</div>
    <div>
        <h1>Hola, {{ $est->nombres }} 👋</h1>
        <p>{{ $est->codigo }} · {{ $academia->nombre_academia ?? '' }}</p>
    </div>
</div>

<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'book'])</div><div><div class="stat__num">{{ $est->matriculas->where('estado','activa')->count() }}</div><div class="stat__label">Cursos activos</div></div></div>
    <div class="card stat"><div class="stat__icon bg-orange">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num">{{ $m }} {{ number_format($pendiente,0) }}</div><div class="stat__label">Saldo pendiente</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'star'])</div><div><div class="stat__num">{{ number_format($promedio,1) }}</div><div class="stat__label">Promedio</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'check'])</div><div><div class="stat__num">{{ $tasaAsis }}%</div><div class="stat__label">Asistencia</div></div></div>
</div>

<div class="grid grid-2" style="margin-bottom:20px">
    <div class="card">
        <h3 class="card__title">Mis cursos</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Curso</th><th>Docente</th><th>Horario</th></tr></thead>
            <tbody>
            @forelse($est->matriculas->where('estado','activa') as $mat)
                <tr><td>{{ $mat->curso->nombre ?? '—' }}</td><td>{{ $mat->curso->docente->nombre_completo ?? '—' }}</td><td>{{ $mat->curso->horario ?? '—' }}</td></tr>
            @empty<tr><td colspan="3" class="empty">No tienes cursos activos.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
    <div class="card">
        <div class="flex between center" style="margin-bottom:10px"><h3 class="card__title mb-0">Mis pagos</h3>
            @if($pendiente>0)<span class="badge badge--yellow">Debes {{ $m }} {{ number_format($pendiente,2) }}</span>@endif</div>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Concepto</th><th>Monto</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($pagos as $p)
                <tr>
                    <td>{{ $p->concepto }}</td>
                    <td>{{ $m }} {{ number_format($p->monto,2) }}</td>
                    <td><span class="badge badge--{{ $p->estado=='pagado'?'green':($p->estado=='pendiente'?'yellow':'red') }}">{{ ucfirst($p->estado) }}</span></td>
                    <td>@if($p->estado=='pendiente')<a href="{{ route('checkout.show',$p) }}" class="btn btn--primary btn--sm">Pagar</a>@else<a href="{{ route('pagos.recibo',$p) }}" target="_blank" class="btn btn--light btn--sm">Recibo</a>@endif</td>
                </tr>
            @empty<tr><td colspan="4" class="empty">Sin pagos registrados.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <h3 class="card__title">Mis calificaciones</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Curso</th><th>Evaluación</th><th>Nota</th></tr></thead>
            <tbody>
            @forelse($calif as $c)
                <tr><td>{{ $c->curso->nombre ?? '—' }}</td><td>{{ $c->evaluacion }}</td>
                    <td><span class="badge badge--{{ $c->nota>=51?'green':'red' }}">{{ number_format($c->nota,1) }}</span></td></tr>
            @empty<tr><td colspan="3" class="empty">Sin calificaciones.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
    <div class="card">
        <h3 class="card__title">Mi asistencia reciente</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Fecha</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($asis->sortByDesc('fecha')->take(10) as $a)
                <tr><td>{{ optional($a->fecha)->format('d/m/Y') }}</td>
                    <td>@php $bb=['presente'=>'green','tardanza'=>'yellow','ausente'=>'red','justificado'=>'blue'][$a->estado]??'gray'; @endphp<span class="badge badge--{{ $bb }}">{{ ucfirst($a->estado) }}</span></td></tr>
            @empty<tr><td colspan="2" class="empty">Sin registros.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
</div>
@endsection
