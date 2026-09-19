<div class="form-grid">
    <div class="form-group"><label>Nombre completo *</label>
        <input class="input" name="name" value="{{ old('name',$usuario->name) }}" required>
        @error('name')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Correo electrónico *</label>
        <input class="input" type="email" name="email" value="{{ old('email',$usuario->email) }}" required>
        @error('email')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Rol *</label>
        <select class="input" name="rol" required>
            @foreach($roles as $k=>$v)<option value="{{ $k }}" @selected(old('rol',$usuario->rol)==$k)>{{ $v }}</option>@endforeach
        </select></div>
    <div class="form-group"></div>
    <div class="form-group"><label>{{ $usuario->exists ? 'Nueva contraseña' : 'Contraseña *' }}</label>
        <input class="input" type="password" name="password" {{ $usuario->exists ? '' : 'required' }} placeholder="{{ $usuario->exists ? 'Dejar en blanco para no cambiar' : '' }}">
        @error('password')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Confirmar contraseña</label>
        <input class="input" type="password" name="password_confirmation" {{ $usuario->exists ? '' : 'required' }}></div>
</div>
<div class="form-actions">
    <button class="btn btn--primary">@include('layouts.icons',['i'=>'check']) Guardar</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn--light">Cancelar</a>
</div>
