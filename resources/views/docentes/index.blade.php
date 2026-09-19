@extends('layouts.app')
@section('title', 'Docentes')
@section('content')
<div class="page-head">
    <div><h1>Docentes</h1><p>Plantel de profesores de la academia</p></div>
    <a href="{{ route('docentes.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nuevo docente</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar por nombre o especialidad..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('docentes.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Código</th><th>Nombre</th><th>Especialidad</th><th>Cursos</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($docentes as $d)
            <tr>
                <td><b>{{ $d->codigo }}</b></td>
                <td>{{ $d->nombre_completo }}</td>
                <td>{{ $d->especialidad ?: '—' }}</td>
                <td>{{ $d->cursos_count }}</td>
                <td><span class="badge badge--{{ $d->estado=='activo'?'green':'gray' }}">{{ ucfirst($d->estado) }}</span></td>
                <td class="act">
                    <a href="{{ route('docentes.show',$d) }}">@include('layouts.icons',['i'=>'eye'])</a>
                    <a href="{{ route('docentes.edit',$d) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('docentes.destroy',$d) }}" data-confirm="¿Eliminar docente?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="6" class="empty">No hay docentes.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $docentes->links() }}
</div>
@endsection
