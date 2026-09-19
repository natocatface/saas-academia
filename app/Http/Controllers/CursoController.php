<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $cursos = Curso::with('docente')
            ->withCount(['matriculas' => fn($x) => $x->where('estado', 'activa')])
            ->when($q, fn($query) => $query
                ->where('nombre', 'like', "%$q%")
                ->orWhere('codigo', 'like', "%$q%"))
            ->latest()->paginate(10)->withQueryString();

        return view('cursos.index', compact('cursos', 'q'));
    }

    public function create()
    {
        return view('cursos.create', [
            'curso' => new Curso(),
            'docentes' => Docente::where('estado', 'activo')->orderBy('nombres')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (\App\Support\LimitePlan::alcanzado('cursos')) {
            return back()->withInput()->with('error', 'Alcanzaste el límite de cursos de tu plan. Mejora tu plan para crear más.');
        }

        $data = $this->validar($request);
        $data['codigo'] = 'CUR-' . str_pad(Curso::withoutGlobalScope('academia')->max('id') + 1, 4, '0', STR_PAD_LEFT);
        Curso::create($data);
        return redirect()->route('cursos.index')->with('ok', 'Curso creado correctamente.');
    }

    public function show(Curso $curso)
    {
        $curso->load(['docente', 'matriculas.estudiante']);
        return view('cursos.show', compact('curso'));
    }

    public function edit(Curso $curso)
    {
        return view('cursos.edit', [
            'curso' => $curso,
            'docentes' => Docente::where('estado', 'activo')->orderBy('nombres')->get(),
        ]);
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update($this->validar($request));
        return redirect()->route('cursos.index')->with('ok', 'Curso actualizado correctamente.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('ok', 'Curso eliminado.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'docente_id' => ['nullable', 'exists:docentes,id'],
            'nivel' => ['required', 'in:basico,intermedio,avanzado'],
            'modalidad' => ['required', 'in:presencial,virtual,hibrido'],
            'horario' => ['nullable', 'string', 'max:120'],
            'aula' => ['nullable', 'string', 'max:50'],
            'cupo_maximo' => ['required', 'integer', 'min:1', 'max:500'],
            'duracion_meses' => ['required', 'integer', 'min:1', 'max:60'],
            'costo_matricula' => ['required', 'numeric', 'min:0'],
            'costo_mensual' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'in:activo,inactivo,finalizado'],
        ]);
    }
}
