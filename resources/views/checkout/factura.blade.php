@extends($layout)
@section('title', 'Pagar suscripción')
@section('content')
<div style="max-width:880px;margin:0 auto">
    <div class="page-head"><div><h1>Pagar suscripción</h1><p>Factura {{ $factura->numero }} · {{ $factura->periodo }}</p></div>
        <a href="{{ route('miplan.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>

    <div class="demo-note">🔒 Pago de demostración — no se realiza ningún cargo real.</div>

    <div class="checkout">
        <div class="card">
            <h3 class="card__title">Datos de la tarjeta</h3>
            <form method="POST" action="{{ route('checkout.facturaPay',$factura) }}">
                @csrf
                <div class="form-group" style="margin-bottom:14px"><label>Titular *</label><input class="input" name="titular" value="{{ old('titular') }}" required>@error('titular')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group" style="margin-bottom:14px"><label>Número de tarjeta *</label><input class="input" name="numero" placeholder="4242 4242 4242 4242" required>@error('numero')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-grid">
                    <div class="form-group"><label>Vencimiento *</label><input class="input" name="vencimiento" placeholder="MM/AA" required></div>
                    <div class="form-group"><label>CVV *</label><input class="input" name="cvv" placeholder="123" required></div>
                </div>
                <button class="btn btn--primary w-100" style="justify-content:center;padding:13px;margin-top:18px;font-size:15px">
                    @include('layouts.icons',['i'=>'card']) Pagar Bs {{ number_format($factura->monto,2) }}
                </button>
            </form>
        </div>
        <div class="card" style="align-self:start">
            <h3 class="card__title">Resumen</h3>
            <div class="dl" style="grid-template-columns:1fr">
                <div class="dl__row"><span>Plan</span><span>{{ $factura->suscripcion->plan->nombre ?? $factura->academia->plan->nombre ?? '—' }}</span></div>
                <div class="dl__row"><span>Periodo</span><span>{{ $factura->periodo }}</span></div>
                <div class="dl__row"><span>Total</span><span>Bs {{ number_format($factura->monto,2) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
