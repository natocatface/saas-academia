@extends('layouts.app')
@section('title', 'Roles y permisos')
@section('content')
<div class="page-head"><div><h1>Roles y permisos</h1><p>Define a qué módulos accede cada rol</p></div></div>
<div class="card">
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Rol</th><th>Módulos permitidos</th><th></th></tr></thead>
        <tbody>
        @foreach($roles as $r)
            <tr>
                <td><b>{{ $r->nombre }}</b>@if($r->es_sistema) <span class="badge badge--gray">sistema</span>@endif</td>
                <td>
                    @php $perm = $r->permisos ?? []; @endphp
                    @if(in_array('*',$perm))
                        <span class="badge badge--green">Acceso total</span>
                    @else
                        @forelse($perm as $m)<span class="badge badge--blue" style="margin:2px">{{ $modulos[$m] ?? $m }}</span>@empty<span class="text-muted">Sin acceso</span>@endforelse
                    @endif
                </td>
                <td class="act">
                    @if($r->slug!=='superadmin')<a href="{{ route('roles.edit',$r) }}">@include('layouts.icons',['i'=>'edit'])</a>@endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</div>
@endsection
