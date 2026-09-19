@extends('layouts.portal')
@section('title', 'Calificaciones')
@section('content')
<div class="page-head"><div><h1>Calificaciones · {{ $curso->nombre }}</h1><p>Registra y consulta las notas de tu curso</p></div>
<a href="{{ route('docente.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>

<div class="grid grid-2" style="grid-template-columns:1fr 1.4fr">
    <div class="card">
        <h3 class="card__title">Registrar calificación</h3>
        <form method="POST" action="{{ route('docente.notas.guardar',$curso) }}">
            @csrf
            <div class="form-group" style="margin-bottom:14px"><label>Estudiante *</label>
                <select class="input" name="estudiante_id" required>
                    <option value="">— Selecciona —</option>
                    @foreach($estudiantes as $e)<option value="{{ $e->id }}">{{ $e->nombre_completo }}</option>@endforeach
                </select>@error('estudiante_id')<span class="form-error">{{ $message }}</span>@enderror</div>
            <div class="form-group" style="margin-bottom:14px"><label>Evaluación *</label><input class="input" name="evaluacion" placeholder="Examen parcial 1" required></div>
            <div class="form-group" style="margin-bottom:14px"><label>Nota (0-100) *</label><input class="input" type="number" step="0.01" min="0" max="100" name="nota" required></div>
            <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar nota</button>
        </form>
    </div>
    <div class="card">
        <h3 class="card__title">Calificaciones registradas</h3>
        <div class="table-wrap"><table class="tbl">
            <thead><tr><th>Estudiante</th><th>Evaluación</th><th>Nota</th><th>Fecha</th></tr></thead>
            <tbody>
            @forelse($calificaciones as $c)
                <tr><td>{{ $c->estudiante->nombre_completo ?? '—' }}</td><td>{{ $c->evaluacion }}</td>
                    <td><span class="badge badge--{{ $c->nota>=51?'green':'red' }}">{{ number_format($c->nota,1) }}</span></td>
                    <td>{{ optional($c->fecha)->format('d/m/Y') }}</td></tr>
            @empty<tr><td colspan="4" class="empty">Sin calificaciones aún.</td></tr>@endforelse
            </tbody>
        </table></div>
    </div>
</div>
@endsection
