@extends('layouts.superadmin')
@section('title', 'Academias')
@section('content')
<div class="page-head">
    <div><h1>Academias</h1><p>Todas las academias registradas en la plataforma</p></div>
    <a href="{{ route('superadmin.academias.create') }}" class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'plus']) Nueva academia</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar academia..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('superadmin.academias.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Academia</th><th>Plan</th><th>Usuarios</th><th>Estudiantes</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($academias as $a)
            <tr>
                <td><b>{{ $a->nombre_academia }}</b><br><small class="text-muted">{{ $a->email ?: $a->slug }}</small></td>
                <td>@if($a->plan)<span class="badge" style="background:{{ $a->plan->color }}22;color:{{ $a->plan->color }}">{{ $a->plan->nombre }}</span>@else<span class="text-muted">—</span>@endif</td>
                <td>{{ $a->usuarios_count }}</td>
                <td>{{ $estPorAcademia[$a->id] ?? 0 }}</td>
                <td>@php $b=['activa'=>'green','prueba'=>'yellow','suspendida'=>'red'][$a->estado]??'gray'; @endphp<span class="badge badge--{{ $b }}">{{ ucfirst($a->estado) }}</span></td>
                <td class="act">
                    <a href="{{ route('superadmin.academias.entrar',$a) }}" title="Entrar a administrar" style="background:#ede9ff;color:#6c5ce7">@include('layouts.icons',['i'=>'logout'])</a>
                    <a href="{{ route('superadmin.academias.show',$a) }}" title="Ver">@include('layouts.icons',['i'=>'eye'])</a>
                    <a href="{{ route('superadmin.academias.edit',$a) }}" title="Editar">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('superadmin.academias.destroy',$a) }}" data-confirm="¿Eliminar {{ $a->nombre_academia }} y TODOS sus datos?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="6" class="empty">No hay academias.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $academias->links() }}
</div>
@endsection
