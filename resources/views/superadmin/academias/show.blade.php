@extends('layouts.superadmin')
@section('title', 'Detalle de academia')
@section('content')
<div class="page-head"><div><h1>{{ $academia->nombre_academia }}</h1><p>{{ $academia->email }} · Registrada {{ optional($academia->fecha_registro)->format('d/m/Y') }}</p></div>
<div class="flex gap">
    <a href="{{ route('superadmin.academias.entrar',$academia) }}" class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'logout']) Entrar a administrar</a>
    <a href="{{ route('superadmin.academias.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a>
</div></div>
<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'users'])</div><div><div class="stat__num">{{ $stats['estudiantes'] }}</div><div class="stat__label">Estudiantes</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'user'])</div><div><div class="stat__num">{{ $stats['usuarios'] }}</div><div class="stat__label">Usuarios</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num" style="font-size:18px">{{ $academia->plan->nombre ?? '—' }}</div><div class="stat__label">Plan</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'check'])</div><div><div class="stat__num" style="font-size:18px">{{ ucfirst($academia->estado) }}</div><div class="stat__label">Estado</div></div></div>
</div>
<div class="grid grid-2">
    <div class="card"><h3 class="card__title">Datos</h3><div class="dl" style="grid-template-columns:1fr">
        <div class="dl__row"><span>NIT/RUC</span><span>{{ $academia->ruc_nit ?: '—' }}</span></div>
        <div class="dl__row"><span>Teléfono</span><span>{{ $academia->telefono ?: '—' }}</span></div>
        <div class="dl__row"><span>Dirección</span><span>{{ $academia->direccion ?: '—' }}</span></div>
        <div class="dl__row"><span>Moneda</span><span>{{ $academia->moneda }}</span></div>
        <div class="dl__row"><span>Periodo</span><span>{{ $academia->periodo_actual }}</span></div>
    </div></div>
    <div class="card"><h3 class="card__title">Usuarios de la academia</h3>
        <div class="table-wrap"><table class="tbl"><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th></tr></thead><tbody>
        @forelse($academia->usuarios as $u)<tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td><span class="badge badge--blue">{{ ucfirst($u->rol) }}</span></td></tr>
        @empty<tr><td colspan="3" class="empty">Sin usuarios.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
