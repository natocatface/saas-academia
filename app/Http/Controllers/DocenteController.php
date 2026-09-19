<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $docentes = Docente::when($q, fn($query) => $query
                ->where('nombres', 'like', "%$q%")
                ->orWhere('apellidos', 'like', "%$q%")
                ->orWhere('especialidad', 'like', "%$q%")
                ->orWhere('codigo', 'like', "%$q%"))
            ->withCount('cursos')->latest()->paginate(10)->withQueryString();

        return view('docentes.index', compact('docentes', 'q'));
    }

    public function create() { return view('docentes.create', ['docente' => new Docente()]); }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['codigo'] = 'DOC-' . str_pad(Docente::withoutGlobalScope('academia')->max('id') + 1, 4, '0', STR_PAD_LEFT);
        Docente::create($data);
        return redirect()->route('docentes.index')->with('ok', 'Docente registrado correctamente.');
    }

    public function show(Docente $docente)
    {
        $docente->load('cursos');
        return view('docentes.show', compact('docente'));
    }

    public function edit(Docente $docente) { return view('docentes.edit', compact('docente')); }

    public function update(Request $request, Docente $docente)
    {
        $docente->update($this->validar($request));
        return redirect()->route('docentes.index')->with('ok', 'Docente actualizado correctamente.');
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return redirect()->route('docentes.index')->with('ok', 'Docente eliminado.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'documento' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'especialidad' => ['nullable', 'string', 'max:120'],
            'titulo' => ['nullable', 'string', 'max:120'],
            'fecha_contratacion' => ['nullable', 'date'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);
    }
}
