@extends('layouts.app')
@section('title', 'Comprobante')
@section('content')
<div class="page-head"><div><h1>Comprobante {{ $pago->codigo }}</h1></div>
<a href="{{ route('pagos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card" style="max-width:520px">
    <div class="dl" style="grid-template-columns:1fr">
        <div class="dl__row"><span>Estudiante</span><span>{{ $pago->estudiante->nombre_completo ?? '—' }}</span></div>
        <div class="dl__row"><span>Concepto</span><span>{{ $pago->concepto }}</span></div>
        <div class="dl__row"><span>Monto</span><span>Bs {{ number_format($pago->monto,2) }}</span></div>
        <div class="dl__row"><span>Método</span><span>{{ ucfirst($pago->metodo_pago) }}</span></div>
        <div class="dl__row"><span>Estado</span><span>{{ ucfirst($pago->estado) }}</span></div>
        <div class="dl__row"><span>Fecha</span><span>{{ optional($pago->fecha_pago)->format('d/m/Y') ?: '—' }}</span></div>
    </div>
</div>
@endsection
