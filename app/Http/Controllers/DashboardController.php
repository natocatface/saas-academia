<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\Asistencia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $u = auth()->user();
        if ($u->esSuperAdmin() && ! session('admin_academia_id')) {
            return redirect()->route('superadmin.panel');
        }
        if ($u->esEstudiante()) {
            return redirect()->route('portal.index');
        }
        if ($u->esDocente()) {
            return redirect()->route('docente.index');
        }

        $totalEstudiantes = Estudiante::count();
        $totalCursos      = Curso::count();
        $totalDocentes    = Docente::count();
        $matriculasActivas = Matricula::where('estado', 'activa')->count();

        $ingresosMes = Pago::where('estado', 'pagado')
            ->whereYear('fecha_pago', now()->year)
            ->whereMonth('fecha_pago', now()->month)
            ->sum('monto');

        $pendientesCobro = Pago::where('estado', 'pendiente')->sum('monto');

        // Ingresos por mes (últimos 6 meses) para el gráfico de barras
        $meses = [];
        $ingresos = [];
        $matriculasMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $f = now()->copy()->subMonths($i);
            $meses[] = ucfirst($f->locale('es')->isoFormat('MMM'));
            $ingresos[] = (float) Pago::where('estado', 'pagado')
                ->whereYear('fecha_pago', $f->year)
                ->whereMonth('fecha_pago', $f->month)->sum('monto');
            $matriculasMes[] = Matricula::whereYear('created_at', $f->year)
                ->whereMonth('created_at', $f->month)->count();
        }

        // Distribución de estudiantes por curso (top 5) para anillos / barras
        $porCurso = Curso::withCount(['matriculas' => fn($q) => $q->where('estado', 'activa')])
            ->orderByDesc('matriculas_count')->take(5)->get();

        // Tasa de asistencia global
        $totalAsis = Asistencia::count();
        $presentes = Asistencia::where('estado', 'presente')->count();
        $tasaAsistencia = $totalAsis > 0 ? round($presentes / $totalAsis * 100) : 0;

        // Anillos: 3 indicadores
        $tasaCobro = ($ingresosMes + $pendientesCobro) > 0
            ? round($ingresosMes / ($ingresosMes + $pendientesCobro) * 100) : 0;
        $capacidad = $totalCursos > 0
            ? min(100, round($matriculasActivas / max(1, Curso::sum('cupo_maximo')) * 100)) : 0;

        // Últimas matrículas
        $ultimas = Matricula::with(['estudiante', 'curso'])
            ->latest()->take(6)->get();

        return view('dashboard.index', compact(
            'totalEstudiantes', 'totalCursos', 'totalDocentes', 'matriculasActivas',
            'ingresosMes', 'pendientesCobro', 'meses', 'ingresos', 'matriculasMes',
            'porCurso', 'tasaAsistencia', 'tasaCobro', 'capacidad', 'ultimas'
        ));
    }
}
