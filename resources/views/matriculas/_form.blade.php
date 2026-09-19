<div class="form-grid">
    <div class="form-group"><label>Estudiante *</label><select class="input" name="estudiante_id" required>
        <option value="">— Selecciona —</option>
        @foreach($estudiantes as $e)<option value="{{ $e->id }}" @selected(old('estudiante_id',$matricula->estudiante_id)==$e->id)>{{ $e->codigo }} · {{ $e->nombre_completo }}</option>@endforeach
    </select>@error('estudiante_id')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Curso *</label><select class="input" name="curso_id" id="curso_id" required>
        <option value="">— Selecciona —</option>
        @foreach($cursos as $c)<option value="{{ $c->id }}" data-costo="{{ $c->costo_matricula }}" @selected(old('curso_id',$matricula->curso_id)==$c->id)>{{ $c->nombre }} (Bs {{ number_format($c->costo_matricula,0) }})</option>@endforeach
    </select>@error('curso_id')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Periodo *</label><input class="input" name="periodo" value="{{ old('periodo',$matricula->periodo) }}" required placeholder="2026 / 2026-I"></div>
    <div class="form-group"><label>Fecha de inicio</label><input class="input" type="date" name="fecha_inicio" value="{{ old('fecha_inicio',optional($matricula->fecha_inicio)->format('Y-m-d')) }}"></div>
    <div class="form-group"><label>Monto matrícula (Bs) *</label><input class="input" type="number" step="0.01" name="monto_matricula" id="monto_matricula" value="{{ old('monto_matricula',$matricula->monto_matricula ?? 0) }}" required></div>
    <div class="form-group"><label>Descuento (Bs)</label><input class="input" type="number" step="0.01" name="descuento" value="{{ old('descuento',$matricula->descuento ?? 0) }}"></div>
    <div class="form-group"><label>Estado *</label><select class="input" name="estado">
        @foreach(['activa'=>'Activa','retirada'=>'Retirada','finalizada'=>'Finalizada','suspendida'=>'Suspendida'] as $k=>$v)<option value="{{ $k }}" @selected(old('estado',$matricula->estado ?? 'activa')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group full"><label>Observaciones</label><textarea class="input" name="observaciones" rows="2">{{ old('observaciones',$matricula->observaciones) }}</textarea></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('matriculas.index') }}" class="btn btn--light">Cancelar</a>
</div>
@push('scripts')
<script>
const sel=document.getElementById('curso_id'), monto=document.getElementById('monto_matricula');
if(sel) sel.addEventListener('change',e=>{const o=e.target.selectedOptions[0]; if(o&&o.dataset.costo&&(!monto.value||monto.value=='0'||monto.value=='0.00')) monto.value=o.dataset.costo;});
</script>
@endpush
