@extends('layouts.app')
@section('title', 'Editar curso')
@section('content')
<div class="page-head"><div><h1>Editar curso</h1><p>{{ $curso->codigo }} · {{ $curso->nombre }}</p></div>
<a href="{{ route('cursos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('cursos.update',$curso) }}">@csrf @method('PUT') @include('cursos._form')</form></div>
@endsection
