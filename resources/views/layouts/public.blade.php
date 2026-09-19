<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AcademiaPro SaaS') · Gestión de academias</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="background:#fff">
<header class="pub-nav">
    <div class="pub-nav__in">
        <a href="{{ route('landing') }}" class="pub-brand"><span class="logo">A</span> AcademiaPro</a>
        <div class="pub-nav__sp"></div>
        <a href="{{ route('landing') }}#funciones" class="lnk">Funciones</a>
        <a href="{{ route('landing') }}#precios" class="lnk">Precios</a>
        <a href="{{ route('login') }}" class="lnk">Iniciar sesión</a>
        <a href="{{ route('registro') }}" class="btn btn--grad">🚀 Prueba gratis</a>
    </div>
</header>
@yield('body')
<footer class="pub-foot">
    © {{ date('Y') }} AcademiaPro SaaS · Plataforma de gestión académica · <a href="{{ route('login') }}" style="color:#7fd6ee">Ingresar</a>
</footer>
</body>
</html>
