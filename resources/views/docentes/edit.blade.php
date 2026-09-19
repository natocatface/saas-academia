@extends('layouts.app')
@section('title', 'Editar docente')
@section('content')
<div class="page-head"><div><h1>Editar docente</h1><p>{{ $docente->codigo }} · {{ $docente->nombre_completo }}</p></div>
<a href="{{ route('docentes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('docentes.update',$docente) }}">@csrf @method('PUT') @include('docentes._form')</form></div>
@endsection
