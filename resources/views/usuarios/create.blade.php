@extends('layouts.app')
@section('title', 'Nuevo usuario')
@section('content')
<div class="page-head"><div><h1>Nuevo usuario</h1><p>Crea un acceso al sistema</p></div>
<a href="{{ route('usuarios.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('usuarios.store') }}">@csrf @include('usuarios._form')</form></div>
@endsection
