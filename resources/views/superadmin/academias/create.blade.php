@extends('layouts.superadmin')
@section('title', 'Nueva academia')
@section('content')
<div class="page-head"><div><h1>Nueva academia</h1><p>Registra una academia y su primer administrador</p></div>
<a href="{{ route('superadmin.academias.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<form method="POST" action="{{ route('superadmin.academias.store') }}">
    @csrf
    <div class="card" style="margin-bottom:20px">
        <h3 class="card__title">Datos de la academia</h3>
        @include('superadmin.academias._form')
    </div>
    <div class="card" style="margin-bottom:20px">
        <h3 class="card__title">Administrador de la academia</h3>
        <div class="form-grid">
            <div class="form-group"><label>Nombre *</label><input class="input" name="admin_nombre" value="{{ old('admin_nombre') }}" required>@error('admin_nombre')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Correo *</label><input class="input" type="email" name="admin_email" value="{{ old('admin_email') }}" required>@error('admin_email')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group"><label>Contraseña *</label><input class="input" type="password" name="admin_password" required>@error('admin_password')<span class="form-error">{{ $message }}</span>@enderror</div>
        </div>
    </div>
    <div class="form-actions"><button class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'check']) Crear academia</button>
        <a href="{{ route('superadmin.academias.index') }}" class="btn btn--light">Cancelar</a></div>
</form>
@endsection
