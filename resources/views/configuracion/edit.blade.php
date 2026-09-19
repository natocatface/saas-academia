@extends('layouts.app')
@section('title', 'Configuración')
@section('content')
<div class="page-head"><div><h1>Configuración de la academia</h1><p>Estos datos se usan en el sistema, recibos y reportes</p></div></div>

<div class="grid grid-2">
    <div class="card">
        <h3 class="card__title">Datos generales</h3>
        <form method="POST" action="{{ route('configuracion.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group full"><label>Nombre de la academia *</label>
                    <input class="input" name="nombre_academia" value="{{ old('nombre_academia',$config->nombre_academia) }}" required>
                    @error('nombre_academia')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full"><label>Eslogan</label>
                    <input class="input" name="eslogan" value="{{ old('eslogan',$config->eslogan) }}"></div>
                <div class="form-group"><label>RUC / NIT</label>
                    <input class="input" name="ruc_nit" value="{{ old('ruc_nit',$config->ruc_nit) }}"></div>
                <div class="form-group"><label>Teléfono</label>
                    <input class="input" name="telefono" value="{{ old('telefono',$config->telefono) }}"></div>
                <div class="form-group"><label>Correo</label>
                    <input class="input" type="email" name="email" value="{{ old('email',$config->email) }}"></div>
                <div class="form-group"><label>Sitio web</label>
                    <input class="input" name="sitio_web" value="{{ old('sitio_web',$config->sitio_web) }}"></div>
                <div class="form-group full"><label>Dirección</label>
                    <input class="input" name="direccion" value="{{ old('direccion',$config->direccion) }}"></div>
                <div class="form-group"><label>Moneda *</label>
                    <input class="input" name="moneda" value="{{ old('moneda',$config->moneda) }}" required placeholder="Bs, $, S/"></div>
                <div class="form-group"><label>Periodo actual *</label>
                    <input class="input" name="periodo_actual" value="{{ old('periodo_actual',$config->periodo_actual) }}" required></div>
                <div class="form-group full"><label>Logo (PNG/JPG, máx 2MB)</label>
                    <input class="input" type="file" name="logo" accept="image/*">
                    @error('logo')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar configuración</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3 class="card__title">Vista previa</h3>
        <div style="text-align:center;padding:24px 12px;border:1px dashed var(--line);border-radius:10px">
            @if($config->logo)
                <img src="{{ asset('storage/'.$config->logo) }}" alt="logo" style="max-height:80px;margin-bottom:12px">
            @else
                <div class="login-card__head" style="display:inline-flex;width:64px;height:64px;border-radius:14px;align-items:center;justify-content:center;background:linear-gradient(90deg,var(--header),var(--header-2));font-size:26px;font-weight:800;color:#fff;margin-bottom:12px;padding:0">
                    {{ mb_substr($config->nombre_academia,0,1) }}
                </div>
            @endif
            <h2 style="margin:0;color:var(--navy)">{{ $config->nombre_academia }}</h2>
            <p class="text-muted" style="margin:4px 0">{{ $config->eslogan }}</p>
            <div class="text-muted" style="font-size:13px;line-height:1.7;margin-top:10px">
                {{ $config->direccion }}<br>
                {{ $config->telefono }} · {{ $config->email }}<br>
                Moneda: {{ $config->moneda }} · Periodo: {{ $config->periodo_actual }}
            </div>
        </div>
    </div>
</div>
@endsection
