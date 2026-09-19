@extends('layouts.app')
@section('title', 'Nueva matrícula')
@section('content')
<div class="page-head"><div><h1>Nueva matrícula</h1><p>Inscribe a un estudiante. Se generará el cobro automáticamente.</p></div>
<a href="{{ route('matriculas.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="card"><form method="POST" action="{{ route('matriculas.store') }}">@csrf @include('matriculas._form')</form></div>
@endsection
