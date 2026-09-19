<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Academia;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Pago;
use App\Models\Suscripcion;

class PanelController extends Controller
{
    public function index()
    {
        $totalAcademias = Academia::count();
        $academiasActivas = Academia::where('estado', 'activa')->count();
        $academiasPrueba = Academia::where('estado', 'prueba')->count();
        $totalUsuarios = User::count();
        $totalEstudiantes = Estudiante::count();           // global (sin tenant)
        $ingresoPlataforma = Pago::where('estado', 'pagado')->sum('monto');

        // Ingresos recurrentes mensuales (MRR) según suscripciones activas
        $mrr = Suscripcion::whereIn('estado', ['activa', 'prueba'])->sum('precio');

        // Academias nuevas por mes (6 meses)
        $meses = []; $altas = [];
        for ($i = 5; $i >= 0; $i--) {
            $f = now()->copy()->subMonths($i);
            $meses[] = ucfirst($f->locale('es')->isoFormat('MMM'));
            $altas[] = Academia::whereYear('fecha_registro', $f->year)->whereMonth('fecha_registro', $f->month)->count();
        }

        // Distribución por plan
        $porPlan = Academia::selectRaw('plan_id, COUNT(*) as total')
            ->with('plan')->groupBy('plan_id')->get();

        $recientes = Academia::with('plan')->latest()->take(6)->get();

        return view('superadmin.panel', compact(
            'totalAcademias', 'academiasActivas', 'academiasPrueba', 'totalUsuarios',
            'totalEstudiantes', 'ingresoPlataforma', 'mrr', 'meses', 'altas', 'porPlan', 'recientes'
        ));
    }
}
