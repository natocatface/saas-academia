@extends('layouts.portal')
@section('title', 'Asistencia')
@section('content')
<div class="page-head"><div><h1>Asistencia · {{ $curso->nombre }}</h1><p>{{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM') }}</p></div>
<a href="{{ route('docente.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>

<div class="card" style="margin-bottom:16px">
    <form method="GET" class="toolbar" style="margin-bottom:0">
        <div class="form-group"><label>Fecha</label><input class="input" type="date" name="fecha" value="{{ $fecha }}" onchange="this.form.submit()"></div>
    </form>
</div>

<div class="card">
    @if($estudiantes->isEmpty())
        <p class="empty">Este curso no tiene estudiantes con matrícula activa.</p>
    @else
    <form method="POST" action="{{ route('docente.asistencia.guardar',$curso) }}">
        @csrf
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Estudiante</th><th style="width:380px">Estado</th></tr></thead>
            <tbody>
            @foreach($estudiantes as $e)
                @php $actual = optional($registros->get($e->id))->estado ?? 'presente'; @endphp
                <tr>
                    <td>{{ $e->codigo }} · {{ $e->nombre_completo }}</td>
                    <td><div class="flex gap" style="flex-wrap:wrap">
                        @foreach(['presente'=>'Presente','ausente'=>'Ausente','tardanza'=>'Tardanza','justificado'=>'Justificado'] as $k=>$v)
                            <label style="display:inline-flex;align-items:center;gap:5px;font-weight:400;cursor:pointer"><input type="radio" name="estado[{{ $e->id }}]" value="{{ $k }}" @checked($actual==$k)> {{ $v }}</label>
                        @endforeach
                    </div></td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="form-actions"><button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar asistencia</button></div>
    </form>
    @endif
</div>
@endsection
