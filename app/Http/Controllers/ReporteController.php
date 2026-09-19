<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Asistencia;
use App\Models\Calificacion;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index()
    {
        // ----- Ingresos por mes (últimos 12 meses) -----
        $mesesLbl = []; $ingresosMes = [];
        for ($i = 11; $i >= 0; $i--) {
            $f = now()->copy()->subMonths($i);
            $mesesLbl[] = ucfirst($f->locale('es')->isoFormat('MMM YY'));
            $ingresosMes[] = (float) Pago::where('estado', 'pagado')
                ->whereYear('fecha_pago', $f->year)->whereMonth('fecha_pago', $f->month)->sum('monto');
        }

        $ingresoTotal = Pago::where('estado', 'pagado')->sum('monto');
        $morosidadTotal = Pago::where('estado', 'pendiente')->sum('monto');

        // ----- Morosidad: estudiantes con pagos pendientes -----
        $morosos = Pago::with('estudiante')
            ->where('estado', 'pendiente')
            ->selectRaw('estudiante_id, COUNT(*) as cuotas, SUM(monto) as deuda')
            ->groupBy('estudiante_id')
            ->orderByDesc('deuda')->take(15)->get();

        // ----- Asistencia por curso -----
        $asistenciaCursos = Curso::all()->map(function ($c) {
            $total = Asistencia::where('curso_id', $c->id)->count();
            $pres = Asistencia::where('curso_id', $c->id)->whereIn('estado', ['presente', 'tardanza'])->count();
            return (object) [
                'curso' => $c->nombre,
                'registros' => $total,
                'tasa' => $total > 0 ? round($pres / $total * 100) : 0,
            ];
        })->filter(fn($x) => $x->registros > 0)->values();

        // ----- Rendimiento: promedio de notas por curso -----
        $rendimiento = Curso::all()->map(function ($c) {
            $prom = Calificacion::where('curso_id', $c->id)->avg('nota');
            $aprob = Calificacion::where('curso_id', $c->id)->where('nota', '>=', 51)->count();
            $tot = Calificacion::where('curso_id', $c->id)->count();
            return (object) [
                'curso' => $c->nombre,
                'promedio' => $prom ? round($prom, 1) : 0,
                'aprobacion' => $tot > 0 ? round($aprob / $tot * 100) : 0,
                'evaluaciones' => $tot,
            ];
        })->filter(fn($x) => $x->evaluaciones > 0)->values();

        $totales = [
            'estudiantes' => Estudiante::count(),
            'matriculas' => Matricula::where('estado', 'activa')->count(),
            'ingreso' => $ingresoTotal,
            'morosidad' => $morosidadTotal,
        ];

        // Estado de pagos (para gráfico de dona)
        $pagosEstado = [
            'pagado' => (float) Pago::where('estado', 'pagado')->sum('monto'),
            'pendiente' => (float) Pago::where('estado', 'pendiente')->sum('monto'),
        ];

        return view('reportes.index', compact(
            'mesesLbl', 'ingresosMes', 'morosos', 'asistenciaCursos', 'rendimiento', 'totales', 'pagosEstado'
        ));
    }

    public function imprimir()
    {
        $academia = app()->bound('academia_actual') ? app('academia_actual') : null;

        $ingresoTotal = Pago::where('estado', 'pagado')->sum('monto');
        $morosidadTotal = Pago::where('estado', 'pendiente')->sum('monto');

        $morosos = Pago::with('estudiante')->where('estado', 'pendiente')
            ->selectRaw('estudiante_id, COUNT(*) as cuotas, SUM(monto) as deuda')
            ->groupBy('estudiante_id')->orderByDesc('deuda')->take(20)->get();

        $rendimiento = Curso::all()->map(function ($c) {
            $prom = Calificacion::where('curso_id', $c->id)->avg('nota');
            $tot = Calificacion::where('curso_id', $c->id)->count();
            return (object) ['curso' => $c->nombre, 'promedio' => $prom ? round($prom, 1) : 0, 'evaluaciones' => $tot];
        })->filter(fn($x) => $x->evaluaciones > 0)->values();

        $totales = [
            'estudiantes' => Estudiante::count(),
            'matriculas' => Matricula::where('estado', 'activa')->count(),
            'ingreso' => $ingresoTotal,
            'morosidad' => $morosidadTotal,
        ];

        return view('reportes.imprimir', compact('academia', 'morosos', 'rendimiento', 'totales'));
    }

    public function exportar(string $tipo): StreamedResponse
    {
        $map = [
            'ingresos' => ['Recibo', 'Estudiante', 'Concepto', 'Monto', 'Método', 'Fecha', 'Estado'],
            'morosidad' => ['Código', 'Estudiante', 'Concepto', 'Monto', 'Vencimiento'],
            'rendimiento' => ['Estudiante', 'Curso', 'Evaluación', 'Nota', 'Periodo'],
            'asistencia' => ['Estudiante', 'Curso', 'Fecha', 'Estado'],
        ];

        abort_unless(isset($map[$tipo]), 404);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=reporte_{$tipo}_" . date('Ymd') . ".csv",
        ];

        return response()->stream(function () use ($tipo, $map) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para Excel/UTF-8
            fputcsv($out, $map[$tipo]);

            if ($tipo === 'ingresos') {
                Pago::with('estudiante')->where('estado', 'pagado')->orderBy('fecha_pago')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $p) {
                            fputcsv($out, [$p->codigo, $p->estudiante->nombre_completo ?? '', $p->concepto,
                                $p->monto, $p->metodo_pago, optional($p->fecha_pago)->format('d/m/Y'), $p->estado]);
                        }
                    });
            } elseif ($tipo === 'morosidad') {
                Pago::with('estudiante')->where('estado', 'pendiente')->orderByDesc('monto')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $p) {
                            fputcsv($out, [$p->codigo, $p->estudiante->nombre_completo ?? '', $p->concepto,
                                $p->monto, optional($p->fecha_vencimiento)->format('d/m/Y')]);
                        }
                    });
            } elseif ($tipo === 'rendimiento') {
                Calificacion::with(['estudiante', 'curso'])->orderBy('curso_id')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $c) {
                            fputcsv($out, [$c->estudiante->nombre_completo ?? '', $c->curso->nombre ?? '',
                                $c->evaluacion, $c->nota, $c->periodo]);
                        }
                    });
            } elseif ($tipo === 'asistencia') {
                Asistencia::with(['estudiante', 'curso'])->orderByDesc('fecha')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $a) {
                            fputcsv($out, [$a->estudiante->nombre_completo ?? '', $a->curso->nombre ?? '',
                                optional($a->fecha)->format('d/m/Y'), $a->estado]);
                        }
                    });
            }

            fclose($out);
        }, 200, $headers);
    }
}
