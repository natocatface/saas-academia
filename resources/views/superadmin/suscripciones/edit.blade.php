@extends('layouts.superadmin')
@section('title', 'Editar suscripción')
@section('content')
<div class="page-head"><div><h1>Suscripción</h1><p>{{ $suscripcion->academia->nombre_academia ?? '' }}</p></div>
<a href="{{ route('superadmin.suscripciones.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card" style="max-width:640px"><form method="POST" action="{{ route('superadmin.suscripciones.update',$suscripcion) }}">@csrf @method('PUT')
    <div class="form-grid">
        <div class="form-group"><label>Plan</label><select class="input" name="plan_id">
            <option value="">— Sin plan —</option>
            @foreach($planes as $pl)<option value="{{ $pl->id }}" @selected($suscripcion->plan_id==$pl->id)>{{ $pl->nombre }}</option>@endforeach
        </select></div>
        <div class="form-group"><label>Estado *</label><select class="input" name="estado">
            @foreach(['activa'=>'Activa','prueba'=>'Prueba','vencida'=>'Vencida','cancelada'=>'Cancelada'] as $k=>$v)<option value="{{ $k }}" @selected($suscripcion->estado==$k)>{{ $v }}</option>@endforeach
        </select></div>
        <div class="form-group"><label>Fecha inicio</label><input class="input" type="date" name="fecha_inicio" value="{{ optional($suscripcion->fecha_inicio)->format('Y-m-d') }}"></div>
        <div class="form-group"><label>Fecha fin</label><input class="input" type="date" name="fecha_fin" value="{{ optional($suscripcion->fecha_fin)->format('Y-m-d') }}"></div>
        <div class="form-group"><label>Precio (Bs) *</label><input class="input" type="number" step="0.01" name="precio" value="{{ $suscripcion->precio }}" required></div>
    </div>
    <div class="form-actions"><button class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'check']) Guardar</button>
        <a href="{{ route('superadmin.suscripciones.index') }}" class="btn btn--light">Cancelar</a></div>
</form></div>
@endsection
