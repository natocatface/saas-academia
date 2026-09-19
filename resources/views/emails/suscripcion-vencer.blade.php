@extends('emails.layout')
@section('contenido')
<h2 style="color:#26313f;margin-top:0">Tu suscripción está por vencer</h2>
<p>Hola, la suscripción de <b>{{ $academia->nombre_academia }}</b> vence en <b>{{ $diasRestantes }} día(s)</b>.</p>
<p>Para no perder el acceso a tus datos, renueva tu plan <b>{{ $academia->plan->nombre ?? '' }}</b> antes del vencimiento.</p>
<p style="text-align:center;margin:26px 0">
  <a href="{{ route('login') }}" style="background:#1fbfe6;color:#fff;text-decoration:none;padding:12px 26px;border-radius:6px;font-weight:600">Renovar ahora</a>
</p>
@endsection
