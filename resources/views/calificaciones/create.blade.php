@extends('layouts.app')
@section('title', 'Nueva calificación')
@section('content')
<div class="page-head"><div><h1>Nueva calificación</h1><p>Registra una nota de evaluación</p></div>
<a href="{{ route('calificaciones.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('calificaciones.store') }}">@csrf @include('calificaciones._form')</form></div>
@endsection
