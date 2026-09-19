<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Mi portal') · {{ $academia->nombre_academia ?? 'AcademiaPro' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<div class="portal">
    <div class="portal__bar">
        <span class="logo">{{ mb_strtoupper(mb_substr($academia->nombre_academia ?? 'A',0,1)) }}</span>
        <b>{{ $academia->nombre_academia ?? 'AcademiaPro' }}</b>
        <span class="sp"></span>
        <span style="font-size:13.5px;opacity:.95">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn btn--glass btn--sm" style="color:#fff">Salir</button></form>
    </div>
    <div class="portal__wrap">
        @if(session('ok'))<div class="alert alert--success">@include('layouts.icons',['i'=>'check']) {{ session('ok') }}</div>@endif
        @if(session('error'))<div class="alert alert--error">{{ session('error') }}</div>@endif
        @yield('content')
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
