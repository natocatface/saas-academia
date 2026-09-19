<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') · Super Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<div class="app app--super">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            <span class="logo">S</span>
            <span>AcademiaPro <strong style="color:#b9aef7">SaaS</strong></span>
        </div>
        <nav class="sidebar__nav">
            <div class="nav-section">Plataforma</div>
            <a href="{{ route('superadmin.panel') }}" class="nav-item {{ request()->routeIs('superadmin.panel') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'home']) <span>Panel general</span>
            </a>
            <a href="{{ route('superadmin.academias.index') }}" class="nav-item {{ request()->routeIs('superadmin.academias.*') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'teacher']) <span>Academias</span>
            </a>
            <a href="{{ route('superadmin.planes.index') }}" class="nav-item {{ request()->routeIs('superadmin.planes.*') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'card']) <span>Planes</span>
            </a>
            <a href="{{ route('superadmin.suscripciones.index') }}" class="nav-item {{ request()->routeIs('superadmin.suscripciones.*') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'clipboard']) <span>Suscripciones</span>
            </a>
            <a href="{{ route('superadmin.facturas.index') }}" class="nav-item {{ request()->routeIs('superadmin.facturas.*') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'money']) <span>Facturación</span>
            </a>
            <div class="nav-section">Auditoría</div>
            <a href="{{ route('bitacora.index') }}" class="nav-item {{ request()->routeIs('bitacora.*') ? 'active' : '' }}">
                @include('layouts.icons',['i'=>'chart']) <span>Bitácora</span>
            </a>
        </nav>
    </aside>
    <div class="overlay" id="overlay"></div>

    <div class="main">
        <header class="topbar">
            <span class="hamb" id="hamb">@include('layouts.icons',['i'=>'menu'])</span>
            <div class="topbar__crumb"><span class="super-badge">@include('layouts.icons',['i'=>'star']) SUPER ADMIN</span>
                <span class="sep">›</span><strong>@yield('title','Panel')</strong></div>
            <div class="topbar__spacer"></div>
            <div class="topbar__right">
                <div class="dropdown" data-dropdown>
                    <div class="topbar__user">
                        <span class="avatar">{{ auth()->user()->iniciales() }}</span>
                        <span style="font-size:13.5px">{{ auth()->user()->name }}</span>
                        @include('layouts.icons',['i'=>'chevron'])
                    </div>
                    <div class="dropdown__menu">
                        <a href="{{ route('perfil.edit') }}">@include('layouts.icons',['i'=>'user']) Mi perfil</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit">@include('layouts.icons',['i'=>'logout']) Cerrar sesión</button></form>
                    </div>
                </div>
            </div>
        </header>
        <main class="content">
            @if(session('ok'))<div class="alert alert--success">@include('layouts.icons',['i'=>'check']) {{ session('ok') }}</div>@endif
            @if(session('error'))<div class="alert alert--error">{{ session('error') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
