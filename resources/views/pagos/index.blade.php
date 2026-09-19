@extends('layouts.app')
@section('title', 'Pagos')
@section('content')
<div class="page-head">
    <div><h1>Pagos y Finanzas</h1><p>Control de cobros, cuotas y comprobantes</p></div>
    <a href="{{ route('pagos.create') }}" class="btn btn--primary">@include('layouts.icons',['i'=>'plus']) Registrar pago</a>
</div>
<div class="grid grid-3" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($totalPagado,0) }}</div><div class="stat__label">Total cobrado</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num">Bs {{ number_format($totalPendiente,0) }}</div><div class="stat__label">Pendiente de cobro</div></div></div>
    <div class="card stat"><div class="stat__icon bg-blue">@include('layouts.icons',['i'=>'clipboard'])</div><div><div class="stat__num">{{ $pagos->total() }}</div><div class="stat__label">Registros</div></div></div>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <div class="search"><input class="input" name="q" value="{{ $q }}" placeholder="Buscar por estudiante o concepto..."></div>
        <select class="input" name="estado" style="max-width:170px">
            <option value="">Todos los estados</option>
            @foreach(['pagado'=>'Pagado','pendiente'=>'Pendiente','anulado'=>'Anulado'] as $k=>$v)<option value="{{ $k }}" @selected($estado==$k)>{{ $v }}</option>@endforeach
        </select>
        <button class="btn btn--light">@include('layouts.icons',['i'=>'search']) Filtrar</button>
        @if($q||$estado)<a href="{{ route('pagos.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Recibo</th><th>Estudiante</th><th>Concepto</th><th>Monto</th><th>Método</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($pagos as $p)
            <tr>
                <td><b>{{ $p->codigo }}</b></td>
                <td>{{ $p->estudiante->nombre_completo ?? '—' }}</td>
                <td>{{ $p->concepto }}</td>
                <td><b>Bs {{ number_format($p->monto,2) }}</b></td>
                <td><span class="badge badge--gray">{{ ucfirst($p->metodo_pago) }}</span></td>
                <td>{{ optional($p->fecha_pago)->format('d/m/Y') ?: '—' }}</td>
                <td><span class="badge badge--{{ $p->estado=='pagado'?'green':($p->estado=='pendiente'?'yellow':'red') }}">{{ ucfirst($p->estado) }}</span></td>
                <td class="act">
                    @if($p->estado=='pendiente')
                    <form method="POST" action="{{ route('pagos.marcarPagado',$p) }}" data-confirm="¿Marcar como pagado?">@csrf @method('PATCH')
                        <button type="submit" class="icon-btn" title="Marcar pagado" style="background:#e3f7ee;color:#1f9c6c">@include('layouts.icons',['i'=>'check'])</button></form>
                    @endif
                    <a href="{{ route('pagos.recibo',$p) }}" target="_blank" title="Recibo">@include('layouts.icons',['i'=>'print'])</a>
                    <a href="{{ route('pagos.edit',$p) }}">@include('layouts.icons',['i'=>'edit'])</a>
                    <form method="POST" action="{{ route('pagos.destroy',$p) }}" data-confirm="¿Eliminar pago?">@csrf @method('DELETE')
                        <button type="submit" class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="8" class="empty">No hay pagos registrados.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $pagos->links() }}
</div>
@endsection
