<div class="form-grid">
    <div class="form-group">
        <label>Nombres *</label>
        <input class="input" name="nombres" value="{{ old('nombres',$estudiante->nombres) }}" required>
        @error('nombres')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label>Apellidos *</label>
        <input class="input" name="apellidos" value="{{ old('apellidos',$estudiante->apellidos) }}" required>
        @error('apellidos')<span class="form-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label>Documento / CI</label>
        <input class="input" name="documento" value="{{ old('documento',$estudiante->documento) }}">
    </div>
    <div class="form-group">
        <label>Fecha de nacimiento</label>
        <input class="input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento',optional($estudiante->fecha_nacimiento)->format('Y-m-d')) }}">
    </div>
    <div class="form-group">
        <label>Correo electrónico</label>
        <input class="input" type="email" name="email" value="{{ old('email',$estudiante->email) }}">
    </div>
    <div class="form-group">
        <label>Teléfono</label>
        <input class="input" name="telefono" value="{{ old('telefono',$estudiante->telefono) }}">
    </div>
    <div class="form-group">
        <label>Género</label>
        <select class="input" name="genero">
            <option value="">—</option>
            @foreach(['M'=>'Masculino','F'=>'Femenino','Otro'=>'Otro'] as $k=>$v)
                <option value="{{ $k }}" @selected(old('genero',$estudiante->genero)==$k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Estado *</label>
        <select class="input" name="estado" required>
            @foreach(['activo'=>'Activo','inactivo'=>'Inactivo','egresado'=>'Egresado'] as $k=>$v)
                <option value="{{ $k }}" @selected(old('estado',$estudiante->estado ?? 'activo')==$k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group full">
        <label>Dirección</label>
        <input class="input" name="direccion" value="{{ old('direccion',$estudiante->direccion) }}">
    </div>
    <div class="form-group">
        <label>Apoderado / Tutor</label>
        <input class="input" name="apoderado" value="{{ old('apoderado',$estudiante->apoderado) }}">
    </div>
    <div class="form-group">
        <label>Teléfono del apoderado</label>
        <input class="input" name="telefono_apoderado" value="{{ old('telefono_apoderado',$estudiante->telefono_apoderado) }}">
    </div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('estudiantes.index') }}" class="btn btn--light">Cancelar</a>
</div>
