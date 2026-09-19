<div class="form-grid">
    <div class="form-group"><label>Estudiante *</label><select class="input" name="estudiante_id" required>
        <option value="">— Selecciona —</option>
        @foreach($estudiantes as $e)<option value="{{ $e->id }}" @selected(old('estudiante_id',$calificacion->estudiante_id)==$e->id)>{{ $e->codigo }} · {{ $e->nombre_completo }}</option>@endforeach
    </select>@error('estudiante_id')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Curso *</label><select class="input" name="curso_id" required>
        <option value="">— Selecciona —</option>
        @foreach($cursos as $c)<option value="{{ $c->id }}" @selected(old('curso_id',$calificacion->curso_id)==$c->id)>{{ $c->nombre }}</option>@endforeach
    </select>@error('curso_id')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Evaluación *</label><input class="input" name="evaluacion" value="{{ old('evaluacion',$calificacion->evaluacion) }}" placeholder="Examen parcial 1" required></div>
    <div class="form-group"><label>Nota (0-100) *</label><input class="input" type="number" step="0.01" min="0" max="100" name="nota" value="{{ old('nota',$calificacion->nota) }}" required></div>
    <div class="form-group"><label>Periodo</label><input class="input" name="periodo" value="{{ old('periodo',$calificacion->periodo) }}" placeholder="2026"></div>
    <div class="form-group"><label>Fecha</label><input class="input" type="date" name="fecha" value="{{ old('fecha',optional($calificacion->fecha)->format('Y-m-d')) }}"></div>
    <div class="form-group full"><label>Observación</label><input class="input" name="observacion" value="{{ old('observacion',$calificacion->observacion) }}"></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('calificaciones.index') }}" class="btn btn--light">Cancelar</a>
</div>
