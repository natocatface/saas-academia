<div class="form-grid">
    <div class="form-group full"><label>Nombre de la academia *</label>
        <input class="input" name="nombre_academia" value="{{ old('nombre_academia',$academia->nombre_academia) }}" required>
        @error('nombre_academia')<span class="form-error">{{ $message }}</span>@enderror</div>
    <div class="form-group"><label>Correo de contacto</label><input class="input" type="email" name="email" value="{{ old('email',$academia->email) }}"></div>
    <div class="form-group"><label>Teléfono</label><input class="input" name="telefono" value="{{ old('telefono',$academia->telefono) }}"></div>
    <div class="form-group full"><label>Dirección</label><input class="input" name="direccion" value="{{ old('direccion',$academia->direccion) }}"></div>
    <div class="form-group"><label>Plan *</label><select class="input" name="plan_id">
        <option value="">— Sin plan —</option>
        @foreach($planes as $pl)<option value="{{ $pl->id }}" @selected(old('plan_id',$academia->plan_id)==$pl->id)>{{ $pl->nombre }} (Bs {{ number_format($pl->precio_mensual,0) }}/mes)</option>@endforeach
    </select></div>
    <div class="form-group"><label>Estado *</label><select class="input" name="estado">
        @foreach(['activa'=>'Activa','prueba'=>'Prueba','suspendida'=>'Suspendida'] as $k=>$v)<option value="{{ $k }}" @selected(old('estado',$academia->estado ?? 'prueba')==$k)>{{ $v }}</option>@endforeach
    </select></div>
    <div class="form-group"><label>Moneda *</label><input class="input" name="moneda" value="{{ old('moneda',$academia->moneda ?? 'Bs') }}" required></div>
    <div class="form-group"><label>Periodo actual *</label><input class="input" name="periodo_actual" value="{{ old('periodo_actual',$academia->periodo_actual ?? date('Y')) }}" required></div>
</div>
