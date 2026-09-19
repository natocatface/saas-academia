<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · AcademiaPro</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth">
    {{-- Panel de marca --}}
    <div class="auth__side">
        <div class="auth__brand">
            <div class="auth__logo">A</div>
            <h1>AcademiaPro</h1>
            <div class="tagline">Sistema de Gestión Académica</div>
            <span class="saas-pill">☁️ Aplicación SaaS Multi-Academia</span>
        </div>
        <div class="auth__feats">
            <div class="auth__feat">
                <div class="ic">@include('layouts.icons',['i'=>'users'])</div>
                <div><h4>Estudiantes y matrículas</h4><p>Control total de inscripciones y cobros</p></div>
            </div>
            <div class="auth__feat">
                <div class="ic">@include('layouts.icons',['i'=>'book'])</div>
                <div><h4>Cursos y docentes</h4><p>Horarios, cupos y asignación de profesores</p></div>
            </div>
            <div class="auth__feat">
                <div class="ic">@include('layouts.icons',['i'=>'chart'])</div>
                <div><h4>Reportes avanzados</h4><p>Métricas e ingresos en tiempo real</p></div>
            </div>
            <div class="auth__feat">
                <div class="ic">@include('layouts.icons',['i'=>'card'])</div>
                <div><h4>Pagos y facturación</h4><p>Cuotas, recibos y control de morosidad</p></div>
            </div>
        </div>
        <div class="auth__ministats">
            <div class="m"><b>500+</b><span>Academias</span></div>
            <div class="m"><b>98%</b><span>Satisfacción</span></div>
            <div class="m"><b>24/7</b><span>Soporte</span></div>
        </div>
    </div>

    {{-- Formulario --}}
    <div class="auth__main">
        <h2>Bienvenido de vuelta 👋</h2>
        <p class="muted">Ingresa tus credenciales para acceder al sistema</p>

        @if($errors->any())
            <div class="alert alert--error" style="margin-bottom:18px">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="field">
                <label for="email">Correo electrónico</label>
                <div class="wrap">
                    @include('layouts.icons',['i'=>'user'])
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="tucorreo@academia.com" required autofocus>
                </div>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <div class="wrap">
                    @include('layouts.icons',['i'=>'settings'])
                    <input id="password" type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            <div class="auth__row">
                <label><input type="checkbox" name="remember"> Recordarme</label>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="btn-login">@include('layouts.icons',['i'=>'logout']) Iniciar sesión</button>
        </form>

        <div class="auth__sep">Cuentas de demostración</div>
        <div class="demo-box">
            <div class="h">@include('layouts.icons',['i'=>'star']) Acceso rápido (clic para rellenar)</div>
            <div class="demo-row" data-email="superadmin@saas.com"><span>superadmin@saas.com</span><span class="tag badge--blue">Super Admin</span></div>
            <div class="demo-row" data-email="admin@academia.com"><span>admin@academia.com</span><span class="tag badge--green">Admin</span></div>
            <div class="demo-row" data-email="secretaria@academia.com"><span>secretaria@academia.com</span><span class="tag badge--yellow">Secretaría</span></div>
        </div>

        <p class="auth__foot">¿No tienes cuenta? <a href="{{ route('registro') }}" style="color:var(--header);font-weight:600">Crea tu academia gratis</a></p>
        <p class="auth__foot" style="margin-top:6px">© {{ date('Y') }} AcademiaPro · Todos los derechos reservados</p>
    </div>
</div>
<script>
document.querySelectorAll('.demo-row').forEach(function (r) {
    r.addEventListener('click', function () {
        document.getElementById('email').value = r.dataset.email;
        document.getElementById('password').value = 'password';
        document.getElementById('password').focus();
    });
});
</script>
</body>
</html>
