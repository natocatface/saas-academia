@extends('layouts.app')
@section('title', 'Detalle de matrícula')
@section('content')
<div class="page-head"><div><h1>Matrícula {{ $matricula->codigo }}</h1><p>{{ $matricula->estudiante->nombre_completo ?? '' }} · {{ $matricula->curso->nombre ?? '' }}</p></div>
<a href="{{ route('matriculas.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>
<div class="grid grid-2">
    <div class="card"><h3 class="card__title">Datos de la matrícula</h3><div class="dl" style="grid-template-columns:1fr">
        <div class="dl__row"><span>Estudiante</span><span>{{ $matricula->estudiante->nombre_completo ?? '—' }}</span></div>
        <div class="dl__row"><span>Curso</span><span>{{ $matricula->curso->nombre ?? '—' }}</span></div>
        <div class="dl__row"><span>Docente</span><span>{{ $matricula->curso->docente->nombre_completo ?? '—' }}</span></div>
        <div class="dl__row"><span>Periodo</span><span>{{ $matricula->periodo }}</span></div>
        <div class="dl__row"><span>Inicio</span><span>{{ optional($matricula->fecha_inicio)->format('d/m/Y') ?: '—' }}</span></div>
        <div class="dl__row"><span>Monto</span><span>Bs {{ number_format($matricula->monto_matricula,2) }}</span></div>
        <div class="dl__row"><span>Descuento</span><span>Bs {{ number_format($matricula->descuento,2) }}</span></div>
        <div class="dl__row"><span>Estado</span><span>{{ ucfirst($matricula->estado) }}</span></div>
    </div></div>
    <div class="card"><h3 class="card__title">Pagos asociados</h3>
        <div class="table-wrap"><table class="tbl"><thead><tr><th>Concepto</th><th>Monto</th><th>Estado</th></tr></thead><tbody>
        @forelse($matricula->pagos as $p)<tr><td>{{ $p->concepto }}</td><td>Bs {{ number_format($p->monto,2) }}</td><td><span class="badge badge--{{ $p->estado=='pagado'?'green':($p->estado=='pendiente'?'yellow':'red') }}">{{ ucfirst($p->estado) }}</span></td></tr>
        @empty<tr><td colspan="3" class="empty">Sin pagos.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
