@extends('layouts.portal')
@section('title', 'Panel del docente')
@section('content')
<div class="portal__hi">
    <div class="av">{{ auth()->user()->iniciales() }}</div>
    <div><h1>Hola, {{ $docente->nombres }} 👋</h1><p>{{ $docente->especialidad }} · {{ $academia->nombre_academia ?? '' }}</p></div>
</div>

<div class="grid grid-3" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'book'])</div><div><div class="stat__num">{{ $cursos->count() }}</div><div class="stat__label">Mis cursos</div></div></div>
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'users'])</div><div><div class="stat__num">{{ $cursos->sum('activos') }}</div><div class="stat__label">Estudiantes</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'teacher'])</div><div><div class="stat__num" style="font-size:16px">{{ $docente->nombre_completo }}</div><div class="stat__label">Docente</div></div></div>
</div>

<div class="card">
    <h3 class="card__title">Mis cursos asignados</h3>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Curso</th><th>Horario</th><th>Estudiantes</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($cursos as $c)
            <tr>
                <td><b>{{ $c->nombre }}</b><br><small class="text-muted">{{ ucfirst($c->modalidad) }} · {{ $c->aula }}</small></td>
                <td>{{ $c->horario ?: '—' }}</td>
                <td>{{ $c->activos }}</td>
                <td class="flex gap">
                    <a href="{{ route('docente.asistencia',$c) }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'check']) Asistencia</a>
                    <a href="{{ route('docente.notas',$c) }}" class="btn btn--light btn--sm">@include('layouts.icons',['i'=>'star']) Notas</a>
                </td>
            </tr>
        @empty<tr><td colspan="4" class="empty">No tienes cursos asignados.</td></tr>@endforelse
        </tbody>
    </table></div>
</div>
@endsection
