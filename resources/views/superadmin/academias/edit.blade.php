@extends('layouts.superadmin')
@section('title', 'Editar academia')
@section('content')
<div class="page-head"><div><h1>Editar academia</h1><p>{{ $academia->nombre_academia }}</p></div>
<a href="{{ route('superadmin.academias.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<form method="POST" action="{{ route('superadmin.academias.update',$academia) }}">
    @csrf @method('PUT')
    <div class="card" style="margin-bottom:20px">@include('superadmin.academias._form')</div>
    <div class="form-actions"><button class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'check']) Guardar</button>
        <a href="{{ route('superadmin.academias.index') }}" class="btn btn--light">Cancelar</a></div>
</form>
@endsection
