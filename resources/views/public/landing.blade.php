@extends('layouts.public')
@section('title', 'Software de gestión para academias')
@section('body')
<section class="hero2">
    <span class="badge-pill">🚀 Plataforma SaaS #1 para academias en LATAM</span>
    <h1>Gestiona tu academia<br><span class="grad">de forma inteligente</span></h1>
    <p class="lead">Todo lo que necesitas para administrar estudiantes, pagos, cursos y docentes. Sin complicaciones, desde cualquier dispositivo.</p>
    <div class="cta">
        <a href="{{ route('registro') }}" class="btn btn--grad btn--lg">🚀 Comenzar gratis — 15 días</a>
        <a href="{{ route('landing') }}#precios" class="btn btn--glass btn--lg">🏷️ Ver precios</a>
    </div>
    <div class="hero-stats">
        <div class="s"><b>500+</b><span>Academias activas</span></div>
        <div class="s"><b>50k+</b><span>Estudiantes gestionados</span></div>
        <div class="s"><b>99.9%</b><span>Uptime garantizado</span></div>
        <div class="s"><b>15 días</b><span>Prueba gratuita</span></div>
    </div>
</section>

<section class="section" id="funciones">
    <h2>Todo lo que tu academia necesita</h2>
    <p class="sub">Centraliza la gestión académica y financiera y ahorra horas de trabajo administrativo.</p>
    <div class="feat">
        @php $feats = [
            ['users','bg-blue','Estudiantes y matrículas','Padrón completo, inscripciones y cobros generados automáticamente.'],
            ['money','bg-green','Pagos y finanzas','Cuotas, métodos de pago, recibos imprimibles y control de morosidad.'],
            ['book','bg-yellow','Cursos y docentes','Catálogo de cursos, horarios, cupos y asignación de profesores.'],
            ['check','bg-orange','Asistencia y notas','Control de asistencia diaria y registro de calificaciones.'],
            ['chart','bg-navy','Reportes','Ingresos, rendimiento y asistencia con exportación a Excel.'],
            ['settings','bg-blue','Roles y seguridad','Usuarios con roles, permisos por módulo y bitácora de auditoría.'],
        ]; @endphp
        @foreach($feats as $f)
        <div class="feat__item">
            <div class="feat__ic {{ $f[1] }}">@include('layouts.icons',['i'=>$f[0]])</div>
            <h3>{{ $f[2] }}</h3><p>{{ $f[3] }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="section" id="precios" style="background:var(--bg);max-width:100%">
    <div style="max-width:1140px;margin:0 auto">
        <h2>Planes para cada tamaño de academia</h2>
        <p class="sub">Empieza con una prueba gratis. Cambia o cancela cuando quieras.</p>
        <div class="pricing">
            @foreach($planes as $i => $p)
            <div class="price {{ $i==1 ? 'dest' : '' }}">
                <h3>{{ $p->nombre }}</h3>
                <div class="amt">Bs {{ number_format($p->precio_mensual,0) }}<small>/mes</small></div>
                <ul>
                    <li>{{ $p->limiteTexto($p->limite_estudiantes) }} estudiantes</li>
                    <li>{{ $p->limiteTexto($p->limite_usuarios) }} usuarios</li>
                    <li>{{ $p->limiteTexto($p->limite_cursos) }} cursos</li>
                    @foreach($p->lista_caracteristicas as $c)<li>{{ $c }}</li>@endforeach
                </ul>
                <a href="{{ route('registro', ['plan' => $p->slug]) }}" class="btn {{ $i==1 ? 'btn--primary' : 'btn--light' }}" style="justify-content:center">Elegir {{ $p->nombre }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-band">
    <h2>¿Listo para modernizar tu academia?</h2>
    <p style="opacity:.92;margin:0 0 22px">Crea tu cuenta en menos de un minuto.</p>
    <a href="{{ route('registro') }}" class="btn btn--white btn--lg">Crear cuenta gratis</a>
</section>
@endsection
