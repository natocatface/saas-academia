@extends('layouts.app')
@section('title', 'Asistencia')
@section('content')
<div class="page-head"><div><h1>Control de Asistencia</h1><p>Registra la asistencia diaria por curso</p></div></div>
<div class="card" style="margin-bottom:20px">
    <form method="GET" class="toolbar" style="margin-bottom:0">
        <div class="form-group" style="min-width:260px"><label>Curso</label>
            <select class="input" name="curso_id" onchange="this.form.submit()">
                <option value="">— Selecciona un curso —</option>
                @foreach($cursos as $c)<option value="{{ $c->id }}" @selected($cursoId==$c->id)>{{ $c->nombre }}</option>@endforeach
            </select>
        </div>
        <div class="form-group"><label>Fecha</label><input class="input" type="date" name="fecha" value="{{ $fecha }}" onchange="this.form.submit()"></div>
    </form>
</div>

@if($cursoId)
<div class="card">
    <h3 class="card__title">Lista de estudiantes · {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM') }}</h3>
    @if($estudiantes->isEmpty())
        <p class="empty">Este curso no tiene estudiantes con matrícula activa.</p>
    @else
    <form method="POST" action="{{ route('asistencias.store') }}">
        @csrf
        <input type="hidden" name="curso_id" value="{{ $cursoId }}">
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Código</th><th>Estudiante</th><th style="width:380px">Estado</th></tr></thead>
            <tbody>
            @foreach($estudiantes as $e)
                @php $actual = optional($registros->get($e->id))->estado ?? 'presente'; @endphp
                <tr>
                    <td>{{ $e->codigo }}</td>
                    <td>{{ $e->nombre_completo }}</td>
                    <td>
                        <div class="flex gap" style="flex-wrap:wrap">
                        @foreach(['presente'=>'Presente','ausente'=>'Ausente','tardanza'=>'Tardanza','justificado'=>'Justificado'] as $k=>$v)
                            <label style="display:inline-flex;align-items:center;gap:5px;font-weight:400;cursor:pointer">
                                <input type="radio" name="estado[{{ $e->id }}]" value="{{ $k }}" @checked($actual==$k)> {{ $v }}
                            </label>
                        @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="form-actions"><button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar asistencia</button></div>
    </form>
    @endif
</div>
@else
<div class="card"><p class="empty">Selecciona un curso para registrar la asistencia.</p></div>
@endif
@endsection
