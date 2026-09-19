@extends('layouts.app')
@section('title', 'Nuevo docente')
@section('content')
<div class="page-head"><div><h1>Nuevo docente</h1><p>Registra un profesor</p></div>
<a href="{{ route('docentes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('docentes.store') }}">@csrf @include('docentes._form')</form></div>
@endsection
