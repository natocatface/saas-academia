@extends('layouts.app')
@section('title', 'Editar estudiante')
@section('content')
<div class="page-head"><div><h1>Editar estudiante</h1><p>{{ $estudiante->codigo }} · {{ $estudiante->nombre_completo }}</p></div>
<a href="{{ route('estudiantes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card">
    <form method="POST" action="{{ route('estudiantes.update',$estudiante) }}">@csrf @method('PUT') @include('estudiantes._form')</form>
</div>
@endsection
