@extends('layouts.app')
@section('title', 'Mi plan')
@section('content')
@php $sub = $academia->suscripcionActiva; $plan = $academia->plan; @endphp
<div class="page-head"><div><h1>Mi plan y facturación</h1><p>Estado de tu suscripción y tus facturas</p></div></div>

<div class="grid grid-3" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num" style="font-size:18px">{{ $plan->nombre ?? 'Sin plan' }}</div><div class="stat__label">Plan actual</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($plan->precio_mensual ?? 0,0) }}</div><div class="stat__label">Mensualidad</div></div></div>
    @php $estado = $sub->estado ?? $academia->estado; $b=['activa'=>'green','prueba'=>'yellow','vencida'=>'red','cancelada'=>'red','suspendida'=>'red'][$estado]??'gray'; @endphp
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'check'])</div><div><div class="stat__num" style="font-size:18px"><span class="badge badge--{{ $b }}">{{ ucfirst($estado) }}</span></div><div class="stat__label">Estado @if($sub && $sub->fecha_fin)· vence {{ $sub->fecha_fin->format('d/m/Y') }}@endif</div></div></div>
</div>

<div class="grid grid-2" style="grid-template-columns:1fr 1.6fr">
    <div class="card">
        <h3 class="card__title">Tu plan incluye</h3>
        @if($plan)
        <div class="dl" style="grid-template-columns:1fr">
            <div class="dl__row"><span>Estudiantes</span><span>{{ $plan->limite_estudiantes>0 ? $plan->limite_estudiantes : 'Ilimitado' }}</span></div>
            <div class="dl__row"><span>Usuarios</span><span>{{ $plan->limite_usuarios>0 ? $plan->limite_usuarios : 'Ilimitado' }}</span></div>
            <div class="dl__row"><span>Cursos</span><span>{{ $plan->limite_cursos>0 ? $plan->limite_cursos : 'Ilimitado' }}</span></div>
        </div>
        @foreach($plan->lista_caracteristicas as $c)<p style="margin:8px 0 0;color:var(--muted);font-size:13.5px">✓ {{ $c }}</p>@endforeach
        @else<p class="empty">No tienes un plan asignado.</p>@endif
    </div>
    <div class="card">
        <h3 class="card__title">Mis facturas</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>N°</th><th>Periodo</th><th>Monto</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            @forelse($facturas as $f)
                <tr><td><b>{{ $f->numero }}</b></td><td>{{ $f->periodo }}</td><td>Bs {{ number_format($f->monto,2) }}</td>
                    <td>@php $bb=['pagada'=>'green','pendiente'=>'yellow','anulada'=>'red'][$f->estado]??'gray'; @endphp<span class="badge badge--{{ $bb }}">{{ ucfirst($f->estado) }}</span></td>
                    <td>@if($f->estado==='pendiente')<a href="{{ route('checkout.factura',$f) }}" class="btn btn--primary btn--sm">Pagar</a>@endif</td></tr>
            @empty<tr><td colspan="5" class="empty">Aún no tienes facturas.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
</div>
@endsection
