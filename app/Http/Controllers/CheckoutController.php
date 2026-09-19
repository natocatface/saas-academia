<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function reglasTarjeta(): array
    {
        return [
            'titular' => ['required', 'string', 'max:120'],
            'numero' => ['required', 'string', 'min:12', 'max:25'],
            'vencimiento' => ['required', 'string', 'max:7'],
            'cvv' => ['required', 'string', 'min:3', 'max:4'],
        ];
    }

    // ---------- Cuota del estudiante ----------
    public function show(Pago $pago)
    {
        $this->autorizarPago($pago);
        abort_if($pago->estado === 'pagado', 403, 'Este pago ya fue realizado.');
        $pago->load('estudiante');
        $layout = auth()->user()->esEstudiante() ? 'layouts.portal' : 'layouts.app';
        return view('checkout.pago', compact('pago', 'layout'));
    }

    public function process(Request $request, Pago $pago)
    {
        $this->autorizarPago($pago);
        $request->validate($this->reglasTarjeta());

        $pago->update([
            'estado' => 'pagado',
            'fecha_pago' => now(),
            'metodo_pago' => 'tarjeta',
            'referencia' => 'TX-' . strtoupper(Str::random(10)),
        ]);

        $destino = auth()->user()->esEstudiante() ? route('portal.index') : route('pagos.index');
        return redirect($destino)->with('ok', "¡Pago realizado con éxito! Comprobante {$pago->codigo}.");
    }

    private function autorizarPago(Pago $pago): void
    {
        $u = auth()->user();
        if ($u->esEstudiante() && $pago->estudiante_id !== $u->estudiante_id) {
            abort(403);
        }
    }

    // ---------- Suscripción de la academia ----------
    public function showFactura(Factura $factura)
    {
        $this->autorizarFactura($factura);
        abort_if($factura->estado === 'pagada', 403, 'Esta factura ya fue pagada.');
        $factura->load('academia', 'suscripcion.plan');
        return view('checkout.factura', ['factura' => $factura, 'layout' => 'layouts.app']);
    }

    public function processFactura(Request $request, Factura $factura)
    {
        $this->autorizarFactura($factura);
        $request->validate($this->reglasTarjeta());

        $factura->update([
            'estado' => 'pagada',
            'fecha_pago' => now(),
            'metodo_pago' => 'tarjeta',
        ]);

        return redirect()->route('miplan.index')->with('ok', "Suscripción pagada. ¡Gracias! Factura {$factura->numero}.");
    }

    private function autorizarFactura(Factura $factura): void
    {
        $u = auth()->user();
        $academiaId = app()->bound('academia_actual_id') ? app('academia_actual_id') : $u->academia_id;
        if (! $u->esAdmin() || $factura->academia_id !== $academiaId) {
            abort(403);
        }
    }
}
