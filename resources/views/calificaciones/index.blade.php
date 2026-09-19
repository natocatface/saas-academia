@extends('layouts.app')
@section('title', 'Calificaciones')
@section('content')
<div class="page-head">
    <div><h1>Calificaciones</h1><p>Registro de evaluaciones y notas</p></div>
    <a href="{{ route('calificaciones.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nueva nota</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar estudiante..."></div>
        <select class="input" name="curso_id" style="max-width:220px">
            <option value="">Todos los cursos</option>
            @foreach($cursos as $c)<option value="{{ $c->id }}" @selected($cursoId==$c->id)>{{ $c->nombre }}</option>@endforeach
        </select>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Filtrar</button>
        @if($q||$cursoId)<a href="{{ route('calificaciones.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Estudiante</th><th>Curso</th><th>Evaluación</th><th>Nota</th><th>Periodo</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($calificaciones as $c)
            <tr>
                <td>{{ $c->estudiante->nombre_completo ?? '—' }}</td>
                <td>{{ $c->curso->nombre ?? '—' }}</td>
                <td>{{ $c->evaluacion }}</td>
                <td>@php $col = $c->nota>=51?'green':'red'; @endphp<span class="badge badge--{{ $col }}">{{ number_format($c->nota,1) }}</span></td>
                <td>{{ $c->periodo ?: '—' }}</td>
                <td>{{ optional($c->fecha)->format('d/m/Y') ?: '—' }}</td>
                <td class="act">
                    <a href="{{ route('calificaciones.edit',$c) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('calificaciones.destroy',$c) }}" data-confirm="¿Eliminar calificación?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="7" class="empty">No hay calificaciones registradas.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $calificaciones->links() }}
</div>
@endsection
