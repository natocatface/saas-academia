@extends('layouts.app')
@section('title', 'Editar pago')
@section('content')
<div class="page-head"><div><h1>Editar pago</h1><p>{{ $pago->codigo }}</p></div>
<a href="{{ route('pagos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('pagos.update',$pago) }}">@csrf @method('PUT') @include('pagos._form')</form></div>
@endsection
