@extends('layouts.superadmin')
@section('title', 'Suscripciones')
@section('content')
<div class="page-head"><div><h1>Suscripciones</h1><p>Estado de las suscripciones de cada academia</p></div></div>
<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'check'])</div><div><div class="stat__num">{{ $resumen['activas'] }}</div><div class="stat__label">Activas</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'clipboard'])</div><div><div class="stat__num">{{ $resumen['prueba'] }}</div><div class="stat__label">En prueba</div></div></div>
    <div class="card stat"><div class="stat__icon bg-red">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num">{{ $resumen['vencidas'] }}</div><div class="stat__label">Vencidas</div></div></div>
    <div class="card stat"><div class="stat__icon" style="background:linear-gradient(135deg,#6c5ce7,#4834b5)">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($resumen['mrr'],0) }}</div><div class="stat__label">MRR</div></div></div>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <select class="input" name="estado" style="max-width:200px" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(['activa'=>'Activa','prueba'=>'Prueba','vencida'=>'Vencida','cancelada'=>'Cancelada'] as $k=>$v)<option value="{{ $k }}" @selected($estado==$k)>{{ $v }}</option>@endforeach
        </select>
        @if($estado)<a href="{{ route('superadmin.suscripciones.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Academia</th><th>Plan</th><th>Precio</th><th>Inicio</th><th>Fin</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @forelse($suscripciones as $s)
            <tr>
                <td><b>{{ $s->academia->nombre_academia ?? '—' }}</b></td>
                <td>{{ $s->plan->nombre ?? '—' }}</td>
                <td>Bs {{ number_format($s->precio,2) }}</td>
                <td>{{ optional($s->fecha_inicio)->format('d/m/Y') ?: '—' }}</td>
                <td>{{ optional($s->fecha_fin)->format('d/m/Y') ?: '—' }}</td>
                <td>@php $b=['activa'=>'green','prueba'=>'yellow','vencida'=>'red','cancelada'=>'gray'][$s->estado]??'gray'; @endphp<span class="badge badge--{{ $b }}">{{ ucfirst($s->estado) }}</span></td>
                <td class="act"><a href="{{ route('superadmin.suscripciones.edit',$s) }}">@include('layouts.icons',['i'=>'edit'])</a></td>
            </tr>
        @empty<tr><td colspan="7" class="empty">No hay suscripciones.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $suscripciones->links() }}
</div>
@endsection
