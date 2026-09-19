<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Asistencia;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocentePanelController extends Controller
{
    private function docente()
    {
        $d = auth()->user()->docente;
        abort_unless($d, 403, 'Tu usuario no está vinculado a un docente.');
        return $d;
    }

    private function cursoDelDocente(Curso $curso): Curso
    {
        abort_unless($curso->docente_id === $this->docente()->id, 403, 'Este curso no te pertenece.');
        return $curso;
    }

    private function estudiantesDe(Curso $curso)
    {
        return Matricula::with('estudiante')->where('curso_id', $curso->id)->where('estado', 'activa')
            ->get()->pluck('estudiante')->filter()->unique('id')->values();
    }

    public function index()
    {
        $docente = $this->docente();
        $cursos = Curso::where('docente_id', $docente->id)
            ->withCount(['matriculas as activos' => fn($q) => $q->where('estado', 'activa')])
            ->get();
        return view('docente.index', compact('docente', 'cursos'));
    }

    public function asistencia(Request $request, Curso $curso)
    {
        $this->cursoDelDocente($curso);
        $fecha = $request->input('fecha', date('Y-m-d'));
        $estudiantes = $this->estudiantesDe($curso);
        $registros = Asistencia::where('curso_id', $curso->id)->where('fecha', $fecha)
            ->get()->keyBy('estudiante_id');
        return view('docente.asistencia', compact('curso', 'fecha', 'estudiantes', 'registros'));
    }

    public function guardarAsistencia(Request $request, Curso $curso)
    {
        $this->cursoDelDocente($curso);
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'array'],
            'estado.*' => ['in:presente,ausente,tardanza,justificado'],
        ]);

        DB::transaction(function () use ($data, $curso) {
            foreach ($data['estado'] as $estudianteId => $estado) {
                Asistencia::updateOrCreate(
                    ['estudiante_id' => $estudianteId, 'curso_id' => $curso->id, 'fecha' => $data['fecha']],
                    ['estado' => $estado, 'academia_id' => $curso->academia_id]
                );
            }
        });

        return redirect()->route('docente.asistencia', ['curso' => $curso->id, 'fecha' => $data['fecha']])
            ->with('ok', 'Asistencia guardada.');
    }

    public function notas(Curso $curso)
    {
        $this->cursoDelDocente($curso);
        $estudiantes = $this->estudiantesDe($curso);
        $calificaciones = Calificacion::with('estudiante')->where('curso_id', $curso->id)->latest()->get();
        return view('docente.notas', compact('curso', 'estudiantes', 'calificaciones'));
    }

    public function guardarNota(Request $request, Curso $curso)
    {
        $this->cursoDelDocente($curso);
        $data = $request->validate([
            'estudiante_id' => ['required', 'exists:estudiantes,id'],
            'evaluacion' => ['required', 'string', 'max:100'],
            'nota' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
        $data['curso_id'] = $curso->id;
        $data['academia_id'] = $curso->academia_id;
        $data['periodo'] = $curso->periodo ?? date('Y');
        $data['fecha'] = now();
        Calificacion::create($data);

        return back()->with('ok', 'Calificación registrada.');
    }
}
