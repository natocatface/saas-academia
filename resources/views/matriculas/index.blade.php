@extends('layouts.app')
@section('title', 'Matrículas')
@section('content')
<div class="page-head">
    <div><h1>Matrículas</h1><p>Inscripciones de estudiantes en cursos</p></div>
    <a href="{{ route('matriculas.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Nueva matrícula</a>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar por estudiante, código o periodo..."></div>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Buscar</button>
        @if($q)<a href="{{ route('matriculas.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Código</th><th>Estudiante</th><th>Curso</th><th>Periodo</th><th>Monto</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($matriculas as $m)
            <tr>
                <td><b>{{ $m->codigo }}</b></td>
                <td>{{ $m->estudiante->nombre_completo ?? '—' }}</td>
                <td>{{ $m->curso->nombre ?? '—' }}</td>
                <td>{{ $m->periodo }}</td>
                <td>Bs {{ number_format($m->monto_matricula - $m->descuento,2) }}</td>
                <td>@php $b=['activa'=>'green','retirada'=>'red','finalizada'=>'blue','suspendida'=>'yellow'][$m->estado]??'gray'; @endphp<span class="badge badge--{{ $b }}">{{ ucfirst($m->estado) }}</span></td>
                <td class="act">
                    <a href="{{ route('matriculas.show',$m) }}">@include('layouts.icons',['i'=>'eye'])</a>
                    <a href="{{ route('matriculas.edit',$m) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('matriculas.destroy',$m) }}" data-confirm="¿Eliminar matrícula?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="7" class="empty">No hay matrículas.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $matriculas->links() }}
</div>
@endsection
