@extends('layouts.app')
@section('title', 'Editar calificación')
@section('content')
<div class="page-head"><div><h1>Editar calificación</h1></div>
<a href="{{ route('calificaciones.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('calificaciones.update',$calificacion) }}">@csrf @method('PUT') @include('calificaciones._form')</form></div>
@endsection
