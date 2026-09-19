<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Academia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');
        $facturas = Factura::with('academia')
            ->when($estado, fn($x) => $x->where('estado', $estado))
            ->latest('fecha_emision')->paginate(15)->withQueryString();

        $resumen = [
            'cobrado' => Factura::where('estado', 'pagada')->sum('monto'),
            'pendiente' => Factura::where('estado', 'pendiente')->sum('monto'),
            'total' => Factura::count(),
        ];

        return view('superadmin.facturas.index', compact('facturas', 'estado', 'resumen'));
    }

    /** Genera la factura del mes en curso para todas las academias con plan (si no existe). */
    public function generarMes()
    {
        $periodo = ucfirst(Carbon::now()->locale('es')->isoFormat('MMMM YYYY'));
        $creadas = 0;

        Academia::with('plan', 'suscripcionActiva')->whereNotNull('plan_id')->each(function ($a) use ($periodo, &$creadas) {
            $existe = Factura::where('academia_id', $a->id)->where('periodo', $periodo)->exists();
            if (! $existe && $a->plan) {
                Factura::create([
                    'numero' => Factura::siguienteNumero(),
                    'academia_id' => $a->id,
                    'suscripcion_id' => optional($a->suscripcionActiva)->id,
                    'periodo' => $periodo,
                    'monto' => $a->plan->precio_mensual,
                    'estado' => 'pendiente',
                    'fecha_emision' => now(),
                ]);
                $creadas++;
            }
        });

        return back()->with('ok', "Se generaron {$creadas} factura(s) para {$periodo}.");
    }

    public function marcarPagada(Request $request, Factura $factura)
    {
        $factura->update([
            'estado' => 'pagada',
            'fecha_pago' => now(),
            'metodo_pago' => $request->input('metodo_pago', 'transferencia'),
        ]);
        return back()->with('ok', "Factura {$factura->numero} marcada como pagada.");
    }

    public function recibo(Factura $factura)
    {
        $factura->load('academia', 'suscripcion.plan');
        return view('superadmin.facturas.recibo', compact('factura'));
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();
        return back()->with('ok', 'Factura eliminada.');
    }
}
