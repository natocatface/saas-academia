<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estudiantes = Estudiante::when($q, function ($query) use ($q) {
                $query->where('nombres', 'like', "%$q%")
                      ->orWhere('apellidos', 'like', "%$q%")
                      ->orWhere('codigo', 'like', "%$q%")
                      ->orWhere('documento', 'like', "%$q%");
            })
            ->latest()->paginate(10)->withQueryString();

        return view('estudiantes.index', compact('estudiantes', 'q'));
    }

    public function create()
    {
        return view('estudiantes.create', ['estudiante' => new Estudiante()]);
    }

    public function store(Request $request)
    {
        if (\App\Support\LimitePlan::alcanzado('estudiantes')) {
            return back()->withInput()->with('error', 'Alcanzaste el límite de estudiantes de tu plan. Mejora tu plan para registrar más.');
        }

        $data = $this->validar($request);
        $data['codigo'] = $this->generarCodigo();
        Estudiante::create($data);

        return redirect()->route('estudiantes.index')->with('ok', 'Estudiante registrado correctamente.');
    }

    public function show(Estudiante $estudiante)
    {
        $estudiante->load(['matriculas.curso', 'pagos']);
        return view('estudiantes.show', compact('estudiante'));
    }

    public function edit(Estudiante $estudiante)
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $estudiante->update($this->validar($request, $estudiante->id));
        return redirect()->route('estudiantes.index')->with('ok', 'Estudiante actualizado correctamente.');
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('ok', 'Estudiante eliminado.');
    }

    private function validar(Request $request, $id = null): array
    {
        return $request->validate([
            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'documento' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'genero' => ['nullable', 'in:M,F,Otro'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'apoderado' => ['nullable', 'string', 'max:120'],
            'telefono_apoderado' => ['nullable', 'string', 'max:30'],
            'estado' => ['required', 'in:activo,inactivo,egresado'],
        ]);
    }

    private function generarCodigo(): string
    {
        $n = Estudiante::withoutGlobalScope('academia')->max('id') + 1;
        return 'EST-' . str_pad($n, 5, '0', STR_PAD_LEFT);
    }
}
