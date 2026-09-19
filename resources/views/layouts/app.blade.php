<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ $academia->nombre_academia ?? config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
@php $u = auth()->user(); @endphp
<body>
<div class="app">
    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            @if(!empty($academia->logo))
                <span class="logo" style="background:#fff;overflow:hidden;padding:2px"><img src="{{ asset('storage/'.$academia->logo) }}" alt="logo" style="max-width:100%;max-height:100%"></span>
            @else
                <span class="logo">{{ mb_strtoupper(mb_substr($academia->nombre_academia ?? 'A',0,1)) }}</span>
            @endif
            <span>{{ $academia->nombre_academia ?? 'AcademiaPro' }}</span>
        </div>
        <nav class="sidebar__nav">
            <div class="nav-section">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                @include('layouts.icons', ['i' => 'home']) <span>Dashboard</span>
            </a>

            @if($u->puede('estudiantes') || $u->puede('matriculas') || $u->puede('cursos') || $u->puede('docentes'))
            <div class="nav-section">Académico</div>
            @if($u->puede('estudiantes'))<a href="{{ route('estudiantes.index') }}" class="nav-item {{ request()->routeIs('estudiantes.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'users']) <span>Estudiantes</span></a>@endif
            @if($u->puede('matriculas'))<a href="{{ route('matriculas.index') }}" class="nav-item {{ request()->routeIs('matriculas.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'clipboard']) <span>Matrículas</span></a>@endif
            @if($u->puede('cursos'))<a href="{{ route('cursos.index') }}" class="nav-item {{ request()->routeIs('cursos.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'book']) <span>Cursos</span></a>@endif
            @if($u->puede('docentes'))<a href="{{ route('docentes.index') }}" class="nav-item {{ request()->routeIs('docentes.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'teacher']) <span>Docentes</span></a>@endif
            @endif

            @if($u->puede('pagos'))
            <div class="nav-section">Finanzas</div>
            <a href="{{ route('pagos.index') }}" class="nav-item {{ request()->routeIs('pagos.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'card']) <span>Pagos</span></a>
            @endif

            @if($u->puede('asistencias') || $u->puede('calificaciones'))
            <div class="nav-section">Control</div>
            @if($u->puede('asistencias'))<a href="{{ route('asistencias.index') }}" class="nav-item {{ request()->routeIs('asistencias.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'check']) <span>Asistencia</span></a>@endif
            @if($u->puede('calificaciones'))<a href="{{ route('calificaciones.index') }}" class="nav-item {{ request()->routeIs('calificaciones.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'star']) <span>Calificaciones</span></a>@endif
            @endif

            @if($u->puede('reportes'))
            <div class="nav-section">Reportes</div>
            <a href="{{ route('reportes.index') }}" class="nav-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'chart']) <span>Reportes</span></a>
            @endif

            @if($u->esAdmin())
            <div class="nav-section">Sistema</div>
            <a href="{{ route('usuarios.index') }}" class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'user']) <span>Usuarios</span></a>
            <a href="{{ route('roles.index') }}" class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'settings']) <span>Roles y permisos</span></a>
            <a href="{{ route('bitacora.index') }}" class="nav-item {{ request()->routeIs('bitacora.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'clipboard']) <span>Bitácora</span></a>
            <a href="{{ route('miplan.index') }}" class="nav-item {{ request()->routeIs('miplan.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'card']) <span>Mi plan</span></a>
            <a href="{{ route('configuracion.edit') }}" class="nav-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">@include('layouts.icons', ['i' => 'cog']) <span>Configuración</span></a>
            @endif
        </nav>
    </aside>
    <div class="overlay" id="overlay"></div>

    {{-- ===================== MAIN ===================== --}}
    <div class="main">
        <header class="topbar">
            <span class="hamb" id="hamb">@include('layouts.icons', ['i' => 'menu'])</span>
            <div class="topbar__crumb">
                <span>Panel de Administración</span>
                <span class="sep">›</span>
                <strong>@yield('title', 'Dashboard')</strong>
            </div>
            <div class="topbar__spacer"></div>
            <div class="topbar__right">
                <div class="dropdown" data-dropdown>
                    <div class="topbar__user">
                        @if($u->avatar)
                            <span class="avatar" style="overflow:hidden;padding:0"><img src="{{ asset('storage/'.$u->avatar) }}" style="width:100%;height:100%;object-fit:cover"></span>
                        @else
                            <span class="avatar">{{ $u->iniciales() }}</span>
                        @endif
                        <span style="font-size:13.5px;">{{ $u->name }}</span>
                        @include('layouts.icons', ['i' => 'chevron'])
                    </div>
                    <div class="dropdown__menu">
                        <a href="{{ route('perfil.edit') }}">@include('layouts.icons', ['i' => 'user']) Mi perfil</a>
                        @if($u->esAdmin())
                        <a href="{{ route('configuracion.edit') }}">@include('layouts.icons', ['i' => 'cog']) Configuración</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">@include('layouts.icons', ['i' => 'logout']) Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            @if($u->esSuperAdmin() && session('admin_academia_id'))
                <div class="impersonate-bar">
                    <span>@include('layouts.icons',['i'=>'eye']) Estás administrando <b>{{ $academia->nombre_academia ?? '' }}</b> como Super Admin.</span>
                    <a href="{{ route('superadmin.academias.salir') }}">Volver al panel de la plataforma</a>
                </div>
            @endif
            @if(session('ok'))
                <div class="alert alert--success">@include('layouts.icons', ['i' => 'check']) {{ session('ok') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert--error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
