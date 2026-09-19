@extends('layouts.superadmin')
@section('title', 'Nuevo plan')
@section('content')
<div class="page-head"><div><h1>Nuevo plan</h1></div><a href="{{ route('superadmin.planes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('superadmin.planes.store') }}">@csrf @include('superadmin.planes._form')</form></div>
@endsection
