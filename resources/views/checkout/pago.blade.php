@extends($layout)
@section('title', 'Pagar cuota')
@section('content')
@php $m = $academia->moneda ?? 'Bs'; @endphp
<div style="max-width:880px;margin:0 auto">
    <div class="page-head"><div><h1>Pagar en línea</h1><p>Pago seguro de tu cuota</p></div>
        <a href="{{ auth()->user()->esEstudiante() ? route('portal.index') : route('pagos.index') }}" class="btn btn--light">@include('layouts.icons',['i'=>'back']) Volver</a></div>

    <div class="demo-note">🔒 Pago de demostración — no se realiza ningún cargo real. Usa cualquier número de tarjeta.</div>

    <div class="checkout">
        <div class="card">
            <h3 class="card__title">Datos de la tarjeta</h3>
            <form method="POST" action="{{ route('checkout.process',$pago) }}" id="payForm">
                @csrf
                <div class="form-group" style="margin-bottom:14px"><label>Titular de la tarjeta *</label>
                    <input class="input" name="titular" value="{{ old('titular', $pago->estudiante->nombre_completo ?? '') }}" required>
                    @error('titular')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-group" style="margin-bottom:14px"><label>Número de tarjeta *</label>
                    <input class="input" name="numero" id="cardNum" placeholder="4242 4242 4242 4242" maxlength="23" required>
                    @error('numero')<span class="form-error">{{ $message }}</span>@enderror</div>
                <div class="form-grid">
                    <div class="form-group"><label>Vencimiento *</label><input class="input" name="vencimiento" placeholder="MM/AA" maxlength="7" required></div>
                    <div class="form-group"><label>CVV *</label><input class="input" name="cvv" placeholder="123" maxlength="4" required></div>
                </div>
                <button class="btn btn--primary w-100" style="justify-content:center;padding:13px;margin-top:18px;font-size:15px">
                    @include('layouts.icons',['i'=>'card']) Pagar {{ $m }} {{ number_format($pago->monto,2) }}
                </button>
            </form>
        </div>

        <div>
            <div class="pay-card">
                <div class="chip"></div>
                <div class="num">•••• •••• •••• ••••</div>
                <div class="row"><span>Titular<b id="pcName">—</b></span><span>Vence<b>••/••</b></span></div>
            </div>
            <div class="card">
                <h3 class="card__title">Resumen</h3>
                <div class="dl" style="grid-template-columns:1fr">
                    <div class="dl__row"><span>Concepto</span><span>{{ $pago->concepto }}</span></div>
                    <div class="dl__row"><span>Estudiante</span><span>{{ $pago->estudiante->nombre_completo ?? '—' }}</span></div>
                    <div class="dl__row"><span>Total</span><span>{{ $m }} {{ number_format($pago->monto,2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const num=document.getElementById('cardNum'), pcName=document.getElementById('pcName');
if(num) num.addEventListener('input',e=>{let v=e.target.value.replace(/\D/g,'').slice(0,16);e.target.value=v.replace(/(.{4})/g,'$1 ').trim();});
const tit=document.querySelector('[name=titular]');
if(tit) tit.addEventListener('input',e=>pcName.textContent=e.target.value||'—');
</script>
@endpush
@endsection
