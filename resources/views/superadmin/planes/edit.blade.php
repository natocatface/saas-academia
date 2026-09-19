@extends('layouts.superadmin')
@section('title', 'Editar plan')
@section('content')
<div class="page-head"><div><h1>Editar plan</h1><p>{{ $plan->nombre }}</p></div><a href="{{ route('superadmin.planes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('superadmin.planes.update',$plan) }}">@csrf @method('PUT') @include('superadmin.planes._form')</form></div>
@endsection
