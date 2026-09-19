<div class="form-grid">
    <div class="form-group"><label>Nombres *</label><input class="input" name="nombres" value="{{ old('nombres',$docente->nombres) }}" required>@error('nombres')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Apellidos *</label><input class="input" name="apellidos" value="{{ old('apellidos',$docente->apellidos) }}" required>@error('apellidos')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Documento / CI</label><input class="input" name="documento" value="{{ old('documento',$docente->documento) }}"></div>
    <div class="form-group"><label>Especialidad</label><input class="input" name="especialidad" value="{{ old('especialidad',$docente->especialidad) }}"></div>
    <div class="form-group"><label>Título profesional</label><input class="input" name="titulo" value="{{ old('titulo',$docente->titulo) }}"></div>
    <div class="form-group"><label>Fecha de contratación</label><input class="input" type="date" name="fecha_contratacion" value="{{ old('fecha_contratacion',optional($docente->fecha_contratacion)->format('Y-m-d')) }}"></div>
    <div class="form-group"><label>Correo electrónico</label><input class="input" type="email" name="email" value="{{ old('email',$docente->email) }}"></div>
    <div class="form-group"><label>Teléfono</label><input class="input" name="telefono" value="{{ old('telefono',$docente->telefono) }}"></div>
    <div class="form-group"><label>Estado *</label><select class="input" name="estado">
        @foreach(['activo'=>'Activo','inactivo'=>'Inactivo'] as $k=>$v)<option value="{{ $k }}" @selected(old('estado',$docente->estado ?? 'activo')==$k)>{{ $v }}</option>@endforeach
    </select></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('docentes.index') }}" class="btn btn--light">Cancelar</a>
</div>
