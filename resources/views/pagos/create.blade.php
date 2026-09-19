@extends('layouts.app')
@section('title', 'Registrar pago')
@section('content')
<div class="page-head"><div><h1>Registrar pago</h1><p>Nuevo cobro o cuota</p></div>
<a href="{{ route('pagos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('pagos.store') }}">@csrf @include('pagos._form')</form></div>
@endsection
