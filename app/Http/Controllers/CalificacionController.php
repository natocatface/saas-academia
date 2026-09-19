<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $cursoId = $request->input('curso_id');
        $calificaciones = Calificacion::with(['estudiante', 'curso'])
            ->when($cursoId, fn($x) => $x->where('curso_id', $cursoId))
            ->when($q, fn($x) => $x->whereHas('estudiante', fn($e) =>
                $e->where('nombres', 'like', "%$q%")->orWhere('apellidos', 'like', "%$q%")))
            ->latest()->paginate(12)->withQueryString();

        $cursos = Curso::orderBy('nombre')->get();
        return view('calificaciones.index', compact('calificaciones', 'cursos', 'q', 'cursoId'));
    }

    public function create()
    {
        return view('calificaciones.create', [
            'calificacion' => new Calificacion(['periodo' => date('Y'), 'fecha' => date('Y-m-d')]),
            'estudiantes' => Estudiante::where('estado', 'activo')->orderBy('apellidos')->get(),
            'cursos' => Curso::where('estado', 'activo')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Calificacion::create($this->validar($request));
        return redirect()->route('calificaciones.index')->with('ok', 'Calificación registrada.');
    }

    public function show(Calificacion $calificacion) { return redirect()->route('calificaciones.index'); }

    public function edit(Calificacion $calificacion)
    {
        return view('calificaciones.edit', [
            'calificacion' => $calificacion,
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),
            'cursos' => Curso::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Calificacion $calificacion)
    {
        $calificacion->update($this->validar($request));
        return redirect()->route('calificaciones.index')->with('ok', 'Calificación actualizada.');
    }

    public function destroy(Calificacion $calificacion)
    {
        $calificacion->delete();
        return redirect()->route('calificaciones.index')->with('ok', 'Calificación eliminada.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'estudiante_id' => ['required', 'exists:estudiantes,id'],
            'curso_id' => ['required', 'exists:cursos,id'],
            'evaluacion' => ['required', 'string', 'max:100'],
            'nota' => ['required', 'numeric', 'min:0', 'max:100'],
            'periodo' => ['nullable', 'string', 'max:30'],
            'fecha' => ['nullable', 'date'],
            'observacion' => ['nullable', 'string', 'max:200'],
        ]);
    }
}
