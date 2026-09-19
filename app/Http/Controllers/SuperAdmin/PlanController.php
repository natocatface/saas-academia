<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $planes = Plan::withCount('academias')->orderBy('precio_mensual')->get();
        return view('superadmin.planes.index', compact('planes'));
    }

    public function create()
    {
        return view('superadmin.planes.create', ['plan' => new Plan(['color' => '#1fbfe6', 'activo' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['slug'] = Str::slug($data['nombre']) . '-' . Str::random(4);
        Plan::create($data);
        return redirect()->route('superadmin.planes.index')->with('ok', 'Plan creado.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.planes.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($this->validar($request));
        return redirect()->route('superadmin.planes.index')->with('ok', 'Plan actualizado.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->academias()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: hay academias usando este plan.');
        }
        $plan->delete();
        return redirect()->route('superadmin.planes.index')->with('ok', 'Plan eliminado.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:60'],
            'precio_mensual' => ['required', 'numeric', 'min:0'],
            'limite_estudiantes' => ['required', 'integer', 'min:0'],
            'limite_usuarios' => ['required', 'integer', 'min:0'],
            'limite_cursos' => ['required', 'integer', 'min:0'],
            'caracteristicas' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:20'],
            'activo' => ['nullable', 'boolean'],
        ]) + ['activo' => $request->boolean('activo')];
    }
}
