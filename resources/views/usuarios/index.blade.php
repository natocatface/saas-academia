@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="page-head">
    <div><h1>Usuarios del sistema</h1><p>Administra los accesos y roles del personal</p></div>
    <a href="{{ route('usuarios.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nuevo usuario</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar por nombre o correo..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('usuarios.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Usuario</th><th>Correo</th><th>Rol</th><th>Registrado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($usuarios as $u)
            <tr>
                <td><div class="flex center gap"><span class="avatar" style="background:var(--header);color:#fff">{{ $u->iniciales() }}</span> {{ $u->name }}</div></td>
                <td>{{ $u->email }}</td>
                <td>@php $b=['admin'=>'blue','secretaria'=>'green','docente'=>'yellow'][$u->rol]??'gray'; @endphp
                    <span class="badge badge--{{ $b }}">{{ \App\Http\Controllers\UsuarioController::ROLES[$u->rol] ?? ucfirst($u->rol) }}</span></td>
                <td>{{ $u->created_at?->format('d/m/Y') }}</td>
                <td class="act">
                    <a href="{{ route('usuarios.edit',$u) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('usuarios.destroy',$u) }}" data-confirm="¿Eliminar usuario {{ $u->name }}?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="5" class="empty">No hay usuarios.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $usuarios->links() }}
</div>
@endsection
