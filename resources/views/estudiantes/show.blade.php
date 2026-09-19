@extends('layouts.app')
@section('title', 'Ficha del estudiante')
@section('content')
<div class="page-head"><div><h1>{{ $estudiante->nombre_completo }}</h1><p>{{ $estudiante->codigo }}</p></div>
<div class="flex gap"><a href="{{ route('estudiantes.edit',$estudiante) }}" class="btn btn--primary">@include('layouts.icons',['i'=>'edit']) Editar</a>
<a href="{{ route('estudiantes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div></div>
<div class="grid grid-2">
    <div class="card">
        <h3 class="card__title">Datos personales</h3>
        <div class="dl" style="grid-template-columns:1fr">
            <div class="dl__row"><span>Documento</span><span>{{ $estudiante->documento ?: '—' }}</span></div>
            <div class="dl__row"><span>Correo</span><span>{{ $estudiante->email ?: '—' }}</span></div>
            <div class="dl__row"><span>Teléfono</span><span>{{ $estudiante->telefono ?: '—' }}</span></div>
            <div class="dl__row"><span>Nacimiento</span><span>{{ optional($estudiante->fecha_nacimiento)->format('d/m/Y') ?: '—' }}</span></div>
            <div class="dl__row"><span>Apoderado</span><span>{{ $estudiante->apoderado ?: '—' }}</span></div>
            <div class="dl__row"><span>Estado</span><span>{{ ucfirst($estudiante->estado) }}</span></div>
        </div>
    </div>
    <div class="card">
        <h3 class="card__title">Cursos matriculados</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Curso</th><th>Periodo</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($estudiante->matriculas as $m)
                <tr><td>{{ $m->curso->nombre ?? '—' }}</td><td>{{ $m->periodo }}</td><td><span class="badge badge--blue">{{ ucfirst($m->estado) }}</span></td></tr>
            @empty<tr><td colspan="3" class="empty">Sin matrículas.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
</div>
@endsection
