<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Calificacion;
use App\Models\Asistencia;

class PortalController extends Controller
{
    public function index()
    {
        $est = auth()->user()->estudiante;
        abort_unless($est, 403, 'Tu usuario no está vinculado a un estudiante.');

        $est->load(['matriculas.curso.docente']);

        $pagos = Pago::where('estudiante_id', $est->id)->latest()->get();
        $pendiente = $pagos->where('estado', 'pendiente')->sum('monto');
        $pagado = $pagos->where('estado', 'pagado')->sum('monto');

        $calif = Calificacion::where('estudiante_id', $est->id)->with('curso')->latest()->get();
        $promedio = round((float) $calif->avg('nota'), 1);

        $asis = Asistencia::where('estudiante_id', $est->id)->get();
        $totalA = $asis->count();
        $pres = $asis->whereIn('estado', ['presente', 'tardanza'])->count();
        $tasaAsis = $totalA > 0 ? round($pres / $totalA * 100) : 0;

        return view('portal.index', compact('est', 'pagos', 'pendiente', 'pagado', 'calif', 'promedio', 'asis', 'tasaAsis'));
    }
}
