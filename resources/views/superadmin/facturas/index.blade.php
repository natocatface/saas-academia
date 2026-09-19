@extends('layouts.superadmin')
@section('title', 'Facturación')
@section('content')
<div class="page-head">
    <div><h1>Facturación</h1><p>Facturas de suscripción de las academias</p></div>
    <form method="POST" action="{{ route('superadmin.facturas.generarMes') }}" data-confirm="¿Generar las facturas del mes en curso para todas las academias con plan?">
        @csrf
        <button class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'plus']) Generar facturas del mes</button>
    </form>
</div>
<div class="grid grid-3" style="margin-bottom:20px">
    <div class="card stat"><div class="stat__icon bg-green">@include('layouts.icons',['i'=>'money'])</div><div><div class="stat__num">Bs {{ number_format($resumen['cobrado'],0) }}</div><div class="stat__label">Cobrado</div></div></div>
    <div class="card stat"><div class="stat__icon bg-yellow">@include('layouts.icons',['i'=>'card'])</div><div><div class="stat__num">Bs {{ number_format($resumen['pendiente'],0) }}</div><div class="stat__label">Por cobrar</div></div></div>
    <div class="card stat"><div class="stat__icon bg-navy">@include('layouts.icons',['i'=>'clipboard'])</div><div><div class="stat__num">{{ $resumen['total'] }}</div><div class="stat__label">Facturas</div></div></div>
</div>
<div class="card">
    <form method="GET" class="toolbar">
        <select class="input" name="estado" style="max-width:190px" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(['pagada'=>'Pagada','pendiente'=>'Pendiente','anulada'=>'Anulada'] as $k=>$v)<option value="{{ $k }}" @selected($estado==$k)>{{ $v }}</option>@endforeach
        </select>
        @if($estado)<a href="{{ route('superadmin.facturas.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>N°</th><th>Academia</th><th>Periodo</th><th>Monto</th><th>Emisión</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($facturas as $f)
            <tr>
                <td><b>{{ $f->numero }}</b></td>
                <td>{{ $f->academia->nombre_academia ?? '—' }}</td>
                <td>{{ $f->periodo }}</td>
                <td><b>Bs {{ number_format($f->monto,2) }}</b></td>
                <td>{{ optional($f->fecha_emision)->format('d/m/Y') }}</td>
                <td>@php $b=['pagada'=>'green','pendiente'=>'yellow','anulada'=>'red'][$f->estado]??'gray'; @endphp<span class="badge badge--{{ $b }}">{{ ucfirst($f->estado) }}</span></td>
                <td class="act">
                    @if($f->estado==='pendiente')
                    <form method="POST" action="{{ route('superadmin.facturas.marcarPagada',$f) }}" data-confirm="¿Marcar como pagada?">@csrf @method('PATCH')
                        <button class="icon-btn" style="background:#e3f7ee;color:#1f9c6c" title="Marcar pagada">@include('layouts.icons',['i'=>'check'])</button></form>
                    @endif
                    <a href="{{ route('superadmin.facturas.recibo',$f) }}" target="_blank" title="Recibo">@include('layouts.icons',['i'=>'print'])</a>
                    <form method="POST" action="{{ route('superadmin.facturas.destroy',$f) }}" data-confirm="¿Eliminar factura?">@csrf @method('DELETE')
                        <button class="icon-btn">@include('layouts.icons',['i'=>'trash'])</button></form>
                </td>
            </tr>
        @empty<tr><td colspan="7" class="empty">No hay facturas. Usa "Generar facturas del mes".</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $facturas->links() }}
</div>
@endsection
