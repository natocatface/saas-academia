<div class="form-grid">
    <div class="form-group"><label>Estudiante *</label><select class="input" name="estudiante_id" required>
        <option value="">— Selecciona —</option>
        @foreach($estudiantes as $e)<option value="{{ $e->id }}" @selected(old('estudiante_id',$pago->estudiante_id)==$e->id)>{{ $e->codigo }} · {{ $e->nombre_completo }}</option>@endforeach
    </select>@error('estudiante_id')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Matrícula (opcional)</label><select class="input" name="matricula_id">
        <option value="">— Ninguna —</option>
        @foreach($matriculas as $m)<option value="{{ $m->id }}" @selected(old('matricula_id',$pago->matricula_id)==$m->id)>{{ $m->codigo }} · {{ $m->curso->nombre ?? '' }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Concepto *</label><input class="input" name="concepto" value="{{ old('concepto',$pago->concepto) }}" placeholder="Mensualidad junio" required></div>
    <div class="form-group"><label>Monto (Bs) *</label><input class="input" type="number" step="0.01" name="monto" value="{{ old('monto',$pago->monto) }}" required></div>
    <div class="form-group"><label>Método de pago *</label><select class="input" name="metodo_pago">
        @foreach(['efectivo'=>'Efectivo','transferencia'=>'Transferencia','tarjeta'=>'Tarjeta','qr'=>'QR','otro'=>'Otro'] as $k=>$v)<option value="{{ $k }}" @selected(old('metodo_pago',$pago->metodo_pago ?? 'efectivo')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Estado *</label><select class="input" name="estado">
        @foreach(['pagado'=>'Pagado','pendiente'=>'Pendiente','anulado'=>'Anulado'] as $k=>$v)<option value="{{ $k }}" @selected(old('estado',$pago->estado ?? 'pendiente')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Fecha de pago</label><input class="input" type="date" name="fecha_pago" value="{{ old('fecha_pago',optional($pago->fecha_pago)->format('Y-m-d')) }}"></div>
    <div class="form-group"><label>Fecha de vencimiento</label><input class="input" type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento',optional($pago->fecha_vencimiento)->format('Y-m-d')) }}"></div>
    <div class="form-group full"><label>Referencia / N° comprobante</label><input class="input" name="referencia" value="{{ old('referencia',$pago->referencia) }}"></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('pagos.index') }}" class="btn btn--light">Cancelar</a>
</div>
