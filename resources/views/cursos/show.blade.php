@extends('layouts.app')
@section('title', 'Detalle del curso')
@section('content')
<div class="page-head"><div><h1>{{ $curso->nombre }}</h1><p>{{ $curso->codigo }} · Docente: {{ $curso->docente->nombre_completo ?? 'Sin asignar' }}</p></div>
<a href="{{ route('cursos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="grid grid-4" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'users'])</div><div><div class="stat__num">{{ $curso->matriculas->where('estado','activa')->count() }}</div><div class="stat__label">Matriculados</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'clipboard'])</div><div><div class="stat__num">{{ $curso->cupos_disponibles }}</div><div class="stat__label">Cupos libres</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($curso->costo_mensual,0) }}</div><div class="stat__label">Mensualidad</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'book'])</div><div><div class="stat__num">{{ $curso->duracion_meses }}m</div><div class="stat__label">Duración</div></div></div>
</div>
<div class="card"><h3 class="card__title">Estudiantes matriculados</h3>
    <div class="table-wrap"><table class="tbl"><thead><tr><th>Código</th><th>Estudiante</th><th>Periodo</th><th>Estado</th></tr></thead><tbody>
    @forelse($curso->matriculas as $m)<tr><td>{{ $m->estudiante->codigo ?? '—' }}</td><td>{{ $m->estudiante->nombre_completo ?? '—' }}</td><td>{{ $m->periodo }}</td><td><span class="badge badge--blue">{{ ucfirst($m->estado) }}</span></td></tr>
    @empty<tr><td colspan="4" class="empty">Sin estudiantes matriculados.</td></tr>@endforelse
    </tbody></table></div>
</div>
@endsection
