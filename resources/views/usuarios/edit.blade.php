@extends('layouts.app')
@section('title', 'Editar usuario')
@section('content')
<div class="page-head"><div><h1>Editar usuario</h1><p>{{ $usuario->name }}</p></div>
<a href="{{ route('usuarios.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('usuarios.update',$usuario) }}">@csrf @method('PUT') @include('usuarios._form')</form></div>
@endsection
