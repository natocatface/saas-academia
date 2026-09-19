<div class="form-grid">
    <div class="form-group full"><label>Nombre del curso *</label><input class="input" name="nombre" value="{{ old('nombre',$curso->nombre) }}" required>@error('nombre')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group full"><label>Descripción</label><textarea class="input" name="descripcion" rows="2">{{ old('descripcion',$curso->descripcion) }}</textarea></div>
    <div class="form-group"><label>Docente</label><select class="input" name="docente_id">
        <option value="">— Sin asignar —</option>
        @foreach($docentes as $d)<option value="{{ $d->id }}" @selected(old('docente_id',$curso->docente_id)==$d->id)>{{ $d->nombre_completo }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Nivel *</label><select class="input" name="nivel">
        @foreach(['basico'=>'Básico','intermedio'=>'Intermedio','avanzado'=>'Avanzado'] as $k=>$v)<option value="{{ $k }}" @selected(old('nivel',$curso->nivel ?? 'basico')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Modalidad *</label><select class="input" name="modalidad">
        @foreach(['presencial'=>'Presencial','virtual'=>'Virtual','hibrido'=>'Híbrido'] as $k=>$v)<option value="{{ $k }}" @selected(old('modalidad',$curso->modalidad ?? 'presencial')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Horario</label><input class="input" name="horario" value="{{ old('horario',$curso->horario) }}" placeholder="Lun-Vie 18:00-20:00"></div>
    <div class="form-group"><label>Aula</label><input class="input" name="aula" value="{{ old('aula',$curso->aula) }}"></div>
    <div class="form-group"><label>Cupo máximo *</label><input class="input" type="number" name="cupo_maximo" value="{{ old('cupo_maximo',$curso->cupo_maximo ?? 30) }}" required></div>
    <div class="form-group"><label>Duración (meses) *</label><input class="input" type="number" name="duracion_meses" value="{{ old('duracion_meses',$curso->duracion_meses ?? 3) }}" required></div>
    <div class="form-group"><label>Costo matrícula (Bs) *</label><input class="input" type="number" step="0.01" name="costo_matricula" value="{{ old('costo_matricula',$curso->costo_matricula ?? 0) }}" required></div>
    <div class="form-group"><label>Costo mensual (Bs) *</label><input class="input" type="number" step="0.01" name="costo_mensual" value="{{ old('costo_mensual',$curso->costo_mensual ?? 0) }}" required></div>
    <div class="form-group"><label>Estado *</label><select class="input" name="estado">
        @foreach(['activo'=>'Activo','inactivo'=>'Inactivo','finalizado'=>'Finalizado'] as $k=>$v)<option value="{{ $k }}" @selected(old('estado',$curso->estado ?? 'activo')==$k)>{{ $v }}</option>@endforeach
    </select></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('cursos.index') }}" class="btn btn--light">Cancelar</a>
</div>
