@extends('layouts.app')
@section('title', 'Ficha del docente')
@section('content')
<div class="page-head"><div><h1>{{ $docente->nombre_completo }}</h1><p>{{ $docente->codigo }} · {{ $docente->especialidad }}</p></div>
<a href="{{ route('docentes.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="grid grid-2">
    <div class="card"><h3 class="card__title">Información</h3>
        <div class="dl" style="grid-template-columns:1fr">
            <div class="dl__row"><span>Documento</span><span>{{ $docente->documento ?: '—' }}</span></div>
            <div class="dl__row"><span>Título</span><span>{{ $docente->titulo ?: '—' }}</span></div>
            <div class="dl__row"><span>Correo</span><span>{{ $docente->email ?: '—' }}</span></div>
            <div class="dl__row"><span>Teléfono</span><span>{{ $docente->telefono ?: '—' }}</span></div>
            <div class="dl__row"><span>Contratación</span><span>{{ optional($docente->fecha_contratacion)->format('d/m/Y') ?: '—' }}</span></div>
        </div>
    </div>
    <div class="card"><h3 class="card__title">Cursos asignados</h3>
        <div class="table-wrap"><table class="tbl"><thead><tr><th>Curso</th><th>Horario</th><th>Estado</th></tr></thead><tbody>
        @forelse($docente->cursos as $c)<tr><td>{{ $c->nombre }}</td><td>{{ $c->horario ?: '—' }}</td><td><span class="badge badge--blue">{{ ucfirst($c->estado) }}</span></td></tr>
        @empty<tr><td colspan="3" class="empty">Sin cursos asignados.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
