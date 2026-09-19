<div class="form-grid">
    <div class="form-group"><label>Nombre del plan *</label><input class="input" name="nombre" value="{{ old('nombre',$plan->nombre) }}" required>@error('nombre')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Precio mensual (Bs) *</label><input class="input" type="number" step="0.01" name="precio_mensual" value="{{ old('precio_mensual',$plan->precio_mensual) }}" required></div>
    <div class="form-group"><label>Límite estudiantes (0 = ilimitado) *</label><input class="input" type="number" name="limite_estudiantes" value="{{ old('limite_estudiantes',$plan->limite_estudiantes ?? 0) }}" required></div>
    <div class="form-group"><label>Límite usuarios (0 = ilimitado) *</label><input class="input" type="number" name="limite_usuarios" value="{{ old('limite_usuarios',$plan->limite_usuarios ?? 0) }}" required></div>
    <div class="form-group"><label>Límite cursos (0 = ilimitado) *</label><input class="input" type="number" name="limite_cursos" value="{{ old('limite_cursos',$plan->limite_cursos ?? 0) }}" required></div>
    <div class="form-group"><label>Color *</label><input class="input" type="color" name="color" value="{{ old('color',$plan->color ?? '#1fbfe6') }}" style="height:42px"></div>
    <div class="form-group full"><label>Características (una por línea)</label><textarea class="input" name="caracteristicas" rows="4">{{ old('caracteristicas',$plan->caracteristicas) }}</textarea></div>
    <div class="form-group"><label style="display:flex;align-items:center;gap:8px;font-weight:400"><input type="checkbox" name="activo" value="1" @checked(old('activo',$plan->activo ?? true)) style="width:auto"> Plan activo (disponible para contratar)</label></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary" style="background:#6c5ce7">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('superadmin.planes.index') }}" class="btn btn--light">Cancelar</a>
</div>
