@extends('layouts.app')
@section('title', 'Editar rol')
@section('content')
<div class="page-head"><div><h1>Permisos del rol</h1><p>{{ $role->nombre }}</p></div>
<a href="{{ route('roles.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card" style="max-width:640px">
    <form method="POST" action="{{ route('roles.update',$role) }}">@csrf @method('PUT')
        <div class="form-group" style="margin-bottom:18px"><label>Nombre del rol *</label>
            <input class="input" name="nombre" value="{{ old('nombre',$role->nombre) }}" required></div>
        <label style="margin-bottom:10px;display:block">Módulos a los que puede acceder</label>
        @php $perm = $role->permisos ?? []; @endphp
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
            @foreach($modulos as $k=>$v)
                <label style="display:flex;align-items:center;gap:9px;font-weight:400;padding:10px 12px;border:1px solid var(--line);border-radius:8px;cursor:pointer">
                    <input type="checkbox" name="permisos[]" value="{{ $k }}" @checked(in_array($k,$perm)) style="width:auto"> {{ $v }}
                </label>
            @endforeach
        </div>
        <div class="form-actions"><button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar permisos</button>
            <a href="{{ route('roles.index') }}" class="btn btn--light">Cancelar</a></div>
    </form>
</div>
@endsection
