<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $matriculas = Matricula::with(['estudiante', 'curso'])
            ->when($q, fn($query) => $query
                ->where('codigo', 'like', "%$q%")
                ->orWhere('periodo', 'like', "%$q%")
                ->orWhereHas('estudiante', fn($e) => $e->where('nombres', 'like', "%$q%")->orWhere('apellidos', 'like', "%$q%")))
            ->latest()->paginate(10)->withQueryString();

        return view('matriculas.index', compact('matriculas', 'q'));
    }

    public function create()
    {
        return view('matriculas.create', [
            'matricula' => new Matricula(['periodo' => date('Y'), 'fecha_inicio' => date('Y-m-d')]),
            'estudiantes' => Estudiante::where('estado', 'activo')->orderBy('apellidos')->get(),
            'cursos' => Curso::where('estado', 'activo')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['codigo'] = 'MAT-' . str_pad(Matricula::withoutGlobalScope('academia')->max('id') + 1, 5, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($data) {
            $matricula = Matricula::create($data);
            // Genera el cobro de matrícula automáticamente
            $neto = max(0, (float) $data['monto_matricula'] - (float) ($data['descuento'] ?? 0));
            if ($neto > 0) {
                Pago::create([
                    'codigo' => 'PAG-' . str_pad(Pago::withoutGlobalScope('academia')->max('id') + 1, 6, '0', STR_PAD_LEFT),
                    'estudiante_id' => $matricula->estudiante_id,
                    'matricula_id' => $matricula->id,
                    'concepto' => 'Matrícula ' . $matricula->periodo,
                    'monto' => $neto,
                    'metodo_pago' => 'efectivo',
                    'fecha_vencimiento' => now()->addDays(7),
                    'estado' => 'pendiente',
                ]);
            }
        });

        return redirect()->route('matriculas.index')->with('ok', 'Matrícula registrada y cobro generado.');
    }

    public function show(Matricula $matricula)
    {
        $matricula->load(['estudiante', 'curso.docente', 'pagos']);
        return view('matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula)
    {
        return view('matriculas.edit', [
            'matricula' => $matricula,
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),
            'cursos' => Curso::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Matricula $matricula)
    {
        $matricula->update($this->validar($request));
        return redirect()->route('matriculas.index')->with('ok', 'Matrícula actualizada.');
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();
        return redirect()->route('matriculas.index')->with('ok', 'Matrícula eliminada.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'estudiante_id' => ['required', 'exists:estudiantes,id'],
            'curso_id' => ['required', 'exists:cursos,id'],
            'periodo' => ['required', 'string', 'max:30'],
            'fecha_inicio' => ['nullable', 'date'],
            'monto_matricula' => ['required', 'numeric', 'min:0'],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', 'in:activa,retirada,finalizada,suspendida'],
            'observaciones' => ['nullable', 'string'],
        ]);
    }
}
