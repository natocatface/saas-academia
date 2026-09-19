@extends('layouts.public')
@section('title', 'Crear cuenta')
@section('body')
<section class="section" style="max-width:620px">
    <h2>Crea tu academia</h2>
    <p class="sub">15 días de prueba gratis. Sin tarjeta de crédito.</p>

    @if($errors->any())
        <div class="alert alert--error" style="margin-bottom:18px">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('registro.store') }}">
            @csrf
            <h3 class="card__title">Datos de la academia</h3>
            <div class="form-group" style="margin-bottom:16px">
                <label>Nombre de la academia *</label>
                <input class="input" name="nombre_academia" value="{{ old('nombre_academia') }}" required>
            </div>
            <div class="form-group" style="margin-bottom:18px">
                <label>Plan *</label>
                <select class="input" name="plan_id" required>
                    @foreach($planes as $p)
                        <option value="{{ $p->id }}" @selected(old('plan_id', $planSel)==$p->slug || old('plan_id')==$p->id)>
                            {{ $p->nombre }} — Bs {{ number_format($p->precio_mensual,0) }}/mes
                        </option>
                    @endforeach
                </select>
            </div>

            <h3 class="card__title">Tu cuenta de administrador</h3>
            <div class="form-grid">
                <div class="form-group full"><label>Nombre completo *</label><input class="input" name="admin_nombre" value="{{ old('admin_nombre') }}" required></div>
                <div class="form-group full"><label>Correo electrónico *</label><input class="input" type="email" name="admin_email" value="{{ old('admin_email') }}" required></div>
                <div class="form-group"><label>Contraseña *</label><input class="input" type="password" name="admin_password" required></div>
                <div class="form-group"><label>Confirmar contraseña *</label><input class="input" type="password" name="admin_password_confirmation" required></div>
            </div>
            <div class="form-actions">
                <button class="btn btn--primary btn--lg" style="justify-content:center;width:100%">Crear mi academia</button>
            </div>
            <p class="text-muted" style="text-align:center;margin:14px 0 0;font-size:13px">¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color:var(--header);font-weight:600">Ingresar</a></p>
        </form>
    </div>
</section>
@endsection
