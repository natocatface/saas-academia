@extends('layouts.app')
@section('title', 'Cursos')
@section('content')
<div class="page-head">
    <div><h1>Cursos</h1><p>Catálogo de cursos y programas de la academia</p></div>
    <a href="{{ route('cursos.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nuevo curso</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar curso..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('cursos.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Código</th><th>Curso</th><th>Docente</th><th>Modalidad</th><th>Cupos</th><th>Mensual</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($cursos as $c)
            <tr>
                <td><b>{{ $c->codigo }}</b></td>
                <td>{{ $c->nombre }}<br><small class="text-muted">{{ ucfirst($c->nivel) }} · {{ $c->horario }}</small></td>
                <td>{{ $c->docente->nombre_completo ?? '—' }}</td>
                <td><span class="badge badge--gray">{{ ucfirst($c->modalidad) }}</span></td>
                <td>{{ $c->matriculas_count }}/{{ $c->cupo_maximo }}</td>
                <td>Bs {{ number_format($c->costo_mensual,0) }}</td>
                <td><span class="badge badge--{{ $c->estado=='activo'?'green':($c->estado=='finalizado'?'blue':'gray') }}">{{ ucfirst($c->estado) }}</span></td>
                <td class="act">
                    <a href="{{ route('cursos.show',$c) }}">@include('layouts.icons',['i'=>'eye'])</a>
                    <a href="{{ route('cursos.edit',$c) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('cursos.destroy',$c) }}" data-confirm="¿Eliminar curso?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="8" class="empty">No hay cursos.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $cursos->links() }}
</div>
@endsection
