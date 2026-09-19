@extends('layouts.app')
@section('title', 'Nuevo estudiante')
@section('content')
<div class="page-head"><div><h1>Nuevo estudiante</h1><p>Registra un nuevo alumno</p></div>
<a href="{{ route('estudiantes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card">
    <form method="POST" action="{{ route('estudiantes.store') }}">@csrf @include('estudiantes._form')</form>
</div>
@endsection
