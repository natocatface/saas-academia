@extends('layouts.app')
@section('title', 'Editar matrícula')
@section('content')
<div class="page-head"><div><h1>Editar matrícula</h1><p>{{ $matricula->codigo }}</p></div>
<a href="{{ route('matriculas.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('matriculas.update',$matricula) }}">@csrf @method('PUT') @include('matriculas._form')</form></div>
@endsection
