@extends('emails.layout')
@section('contenido')
<h2 style="color:#26313f;margin-top:0">¡Bienvenido, {{ $usuario->name }}! 🎉</h2>
<p>Tu academia <b>{{ $usuario->academia->nombre_academia ?? '' }}</b> se creó correctamente y ya tienes <b>15 días de prueba gratis</b>.</p>
<p>Ingresa con tu correo <b>{{ $usuario->email }}</b> para empezar a registrar estudiantes, cursos y pagos.</p>
<p style="text-align:center;margin:26px 0">
  <a href="{{ route('login') }}" style="background:#1fbfe6;color:#fff;text-decoration:none;padding:12px 26px;border-radius:6px;font-weight:600">Ingresar al sistema</a>
</p>
<p style="color:#8a96a3;font-size:13.5px">Si no creaste esta cuenta, ignora este mensaje.</p>
@endsection
