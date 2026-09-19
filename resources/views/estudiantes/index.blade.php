@extends('layouts.app')
@section('title', 'Estudiantes')
@section('content')
<div class="page-head">
    <div><h1>Estudiantes</h1><p>Gestión del padrón de alumnos de la academia</p></div>
    <a href="{{ route('estudiantes.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nuevo estudiante</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre, código o documento..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('estudiantes.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap">
        <table class="tbl">
            <thead><tr><th>Código</th><th>Nombre completo</th><th>Documento</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
            @forelse($estudiantes as $e)
                <tr>
                    <td><b>{{ $e->codigo }}</b></td>
                    <td>{{ $e->nombre_completo }}</td>
                    <td>{{ $e->documento ?: '—' }}</td>
                    <td>{{ $e->telefono ?: '—' }}</td>
                    <td>
                        @php $b=['activo'=>'green','inactivo'=>'gray','egresado'=>'blue'][$e->estado]??'gray'; @endphp
                        <span class="badge badge--{{ $b }}">{{ ucfirst($e->estado) }}</span>
                    </td>
                    <td class="act">
                        <a href="{{ route('estudiantes.show',$e) }}" title="Ver">@include('layouts.icons',['i'=>'eye'])</a>
                        <a href="{{ route('estudiantes.edit',$e) }}" title="Editar">@include('layouts.icons',['i'=>'edit'])</a>
                        <form method="POST" action="{{ route('estudiantes.destroy',$e) }}" data-confirm="¿Eliminar a {{ $e->nombre_completo }}?">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" title="Eliminar">@include('layouts.icons',['i'=>'trash'])</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No se encontraron estudiantes.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $estudiantes->links() }}
</div>
@endsection
