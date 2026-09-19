@extends('layouts.superadmin')
@section('title', 'Planes')
@section('content')
<div class="page-head">
    <div><h1>Planes de suscripción</h1><p>Define los planes que las academias pueden contratar</p></div>
    <a href="{{ route('superadmin.planes.create') }}" class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'plus']) Nuevo plan</a>
</div>
<div class="grid grid-3">
    @foreach($planes as $p)
    <div class="plan-card" style="border-color:{{ $p->color }}55">
        <span class="badge" style="background:{{ $p->color }}22;color:{{ $p->color }}">{{ $p->academias_count }} academias</span>
        <h3 style="margin-top:10px">{{ $p->nombre }}</h3>
        <div class="precio">Bs {{ number_format($p->precio_mensual,0) }}<small>/mes</small></div>
        <ul>
            <li>{{ $p->limiteTexto($p->limite_estudiantes) }} estudiantes</li>
            <li>{{ $p->limiteTexto($p->limite_usuarios) }} usuarios</li>
            <li>{{ $p->limiteTexto($p->limite_cursos) }} cursos</li>
            @foreach($p->lista_caracteristicas as $c)<li>{{ $c }}</li>@endforeach
        </ul>
        <div class="form-actions">
            <a href="{{ route('superadmin.planes.edit',$p) }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'edit']) Editar</a>
            <form method="POST" action="{{ route('superadmin.planes.destroy',$p) }}" data-confirm="¿Eliminar plan?">@csrf @method('DELETE')
                <button class="btn btn--light btn--sm" style="color:var(--red)">@include('layouts.icons',['i'=>'trash']) Eliminar</button></form>
        </div>
    </div>
    @endforeach
</div>
@endsection
