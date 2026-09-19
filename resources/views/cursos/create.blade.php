@extends('layouts.app')
@section('title', 'Nuevo curso')
@section('content')
<div class="page-head"><div><h1>Nuevo curso</h1><p>Crea un curso o programa</p></div>
<a href="{{ route('cursos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('cursos.store') }}">@csrf @include('cursos._form')</form></div>
@endsection
