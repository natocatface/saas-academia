<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $fecha = $request->input('fecha', date('Y-m-d'));
        $cursos = Curso::where('estado', 'activo')->orderBy('nombre')->get();

        $registros = collect();
        if ($cursoId) {
            $registros = Asistencia::with('estudiante')
                ->where('curso_id', $cursoId)->where('fecha', $fecha)
                ->get()->keyBy('estudiante_id');
        }

        $estudiantes = collect();
        if ($cursoId) {
            $estudiantes = Matricula::with('estudiante')
                ->where('curso_id', $cursoId)->where('estado', 'activa')
                ->get()->pluck('estudiante')->filter()->unique('id')->values();
        }

        return view('asistencias.index', compact('cursos', 'cursoId', 'fecha', 'estudiantes', 'registros'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'array'],
            'estado.*' => ['in:presente,ausente,tardanza,justificado'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['estado'] as $estudianteId => $estado) {
                Asistencia::updateOrCreate(
                    ['estudiante_id' => $estudianteId, 'curso_id' => $data['curso_id'], 'fecha' => $data['fecha']],
                    ['estado' => $estado]
                );
            }
        });

        return redirect()->route('asistencias.index', ['curso_id' => $data['curso_id'], 'fecha' => $data['fecha']])
            ->with('ok', 'Asistencia registrada correctamente.');
    }

    public function create() { return redirect()->route('asistencias.index'); }
    public function show($id) { return redirect()->route('asistencias.index'); }
    public function edit($id) { return redirect()->route('asistencias.index'); }
    public function update(Request $r, $id) { return redirect()->route('asistencias.index'); }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return back()->with('ok', 'Registro de asistencia eliminado.');
    }
}
