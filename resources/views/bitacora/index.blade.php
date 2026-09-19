@extends($layout)
@section('title', 'Bitácora')
@section('content')
<div class="page-head"><div><h1>Bitácora de auditoría</h1><p>Registro de acciones realizadas en el sistema</p></div></div>
<div class="card">
    <form method="GET" class="toolbar">
        <select class="input" name="accion" style="max-width:180px" onchange="this.form.submit()">
            <option value="">Todas las acciones</option>
            @foreach(['creó','actualizó','eliminó','ingresó','salió'] as $a)<option value="{{ $a }}" @selected($accion==$a)>{{ ucfirst($a) }}</option>@endforeach
        </select>
        <select class="input" name="modulo" style="max-width:200px" onchange="this.form.submit()">
            <option value="">Todos los módulos</option>
            @foreach($modulos as $m)<option value="{{ $m }}" @selected($modulo==$m)>{{ $m }}</option>@endforeach
        </select>
        @if($accion||$modulo)<a href="{{ route('bitacora.index') }}" class="btn btn--ghost">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Módulo</th><th>Detalle</th><th>IP</th></tr></thead>
        <tbody>
        @forelse($registros as $b)
            <tr>
                <td>{{ optional($b->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $b->usuario->name ?? '—' }}</td>
                <td>@php $c=['creó'=>'green','actualizó'=>'blue','eliminó'=>'red','ingresó'=>'gray','salió'=>'gray'][$b->accion]??'gray'; @endphp<span class="badge badge--{{ $c }}">{{ ucfirst($b->accion) }}</span></td>
                <td>{{ $b->modulo ?: '—' }}</td>
                <td>{{ $b->descripcion ?: '—' }}</td>
                <td class="text-muted">{{ $b->ip ?: '—' }}</td>
            </tr>
        @empty<tr><td colspan="6" class="empty">Sin registros en la bitácora.</td></tr>@endforelse
        </tbody>
    </table></div>
    {{ $registros->links() }}
</div>
@endsection
