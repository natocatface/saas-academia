@extends('emails.layout')
@section('contenido')
<h2 style="color:#26313f;margin-top:0">Recordatorio de pago</h2>
<p>Estimado(a) {{ $estudiante->nombre_completo }},</p>
<p>Le recordamos que tiene pagos pendientes en <b>{{ $nombreAcademia }}</b> por un total de <b>Bs {{ number_format($deuda,2) }}</b>.</p>
<p>Por favor acérquese a la administración para regularizar su situación. Gracias.</p>
@endsection
