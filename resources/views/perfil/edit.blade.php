@php $layout = auth()->user()->esSuperAdmin() && !session('admin_academia_id') ? 'layouts.superadmin' : 'layouts.app'; @endphp
@extends($layout)
@section('title', 'Mi perfil')
@section('content')
<div class="page-head"><div><h1>Mi perfil</h1><p>Actualiza tus datos de acceso</p></div></div>
<div class="grid grid-2" style="grid-template-columns:1fr 1.4fr">
    <div class="card" style="text-align:center">
        @if($usuario->avatar)
            <img src="{{ asset('storage/'.$usuario->avatar) }}" alt="avatar" style="width:110px;height:110px;border-radius:50%;object-fit:cover;margin:6px auto 12px;display:block">
        @else
            <div style="width:110px;height:110px;border-radius:50%;margin:6px auto 12px;background:linear-gradient(135deg,#1fbfe6,#1597c4);color:#fff;display:flex;align-items:center;justify-content:center;font-size:38px;font-weight:700">{{ $usuario->iniciales() }}</div>
        @endif
        <h2 style="margin:0;color:var(--navy)">{{ $usuario->name }}</h2>
        <p class="text-muted" style="margin:4px 0">{{ $usuario->email }}</p>
        <span class="badge badge--blue">{{ ucfirst($usuario->rol) }}</span>
        @if($usuario->academia)<p class="text-muted" style="margin-top:10px;font-size:13px">{{ $usuario->academia->nombre_academia }}</p>@endif
    </div>
    <div class="card">
        <h3 class="card__title">Editar datos</h3>
        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label>Nombre *</label><input class="input" name="name" value="{{ old('name',$usuario->name) }}" required>@error('name')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label>Correo *</label><input class="input" type="email" name="email" value="{{ old('email',$usuario->email) }}" required>@error('email')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group full"><label>Foto de perfil</label><input class="input" type="file" name="avatar" accept="image/*">@error('avatar')<span class="form-error">{{ $message }}</span>@enderror</div>
            </div>
            <h3 class="card__title" style="margin-top:20px">Cambiar contraseña</h3>
            <div class="form-grid">
                <div class="form-group full"><label>Contraseña actual</label><input class="input" type="password" name="password_actual" placeholder="Sólo si vas a cambiarla">@error('password_actual')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label>Nueva contraseña</label><input class="input" type="password" name="password">@error('password')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group"><label>Confirmar nueva</label><input class="input" type="password" name="password_confirmation"></div>
            </div>
            <div class="form-actions"><button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar cambios</button></div>
        </form>
    </div>
</div>
@endsection
