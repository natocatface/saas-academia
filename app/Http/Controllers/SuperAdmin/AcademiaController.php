<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Academia;
use App\Models\Plan;
use App\Models\User;
use App\Models\Suscripcion;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AcademiaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $academias = Academia::with('plan')
            ->withCount('usuarios')
            ->when($q, fn($x) => $x->where('nombre_academia', 'like', "%$q%")->orWhere('email', 'like', "%$q%"))
            ->latest()->paginate(10)->withQueryString();

        // conteo de estudiantes por academia (sin scope)
        $estPorAcademia = Estudiante::selectRaw('academia_id, COUNT(*) as t')->groupBy('academia_id')->pluck('t', 'academia_id');

        return view('superadmin.academias.index', compact('academias', 'q', 'estPorAcademia'));
    }

    public function create()
    {
        return view('superadmin.academias.create', ['academia' => new Academia(['estado' => 'prueba', 'moneda' => 'Bs', 'periodo_actual' => date('Y')]), 'planes' => Plan::where('activo', true)->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request, true);

        DB::transaction(function () use ($data, $request) {
            $academia = Academia::create([
                'nombre_academia' => $data['nombre_academia'],
                'email' => $data['email'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'moneda' => $data['moneda'], 'periodo_actual' => $data['periodo_actual'],
                'plan_id' => $data['plan_id'] ?? null, 'estado' => $data['estado'],
                'fecha_registro' => now(),
            ]);

            $plan = $data['plan_id'] ? Plan::find($data['plan_id']) : null;
            Suscripcion::create([
                'academia_id' => $academia->id, 'plan_id' => $plan?->id,
                'estado' => $data['estado'] === 'prueba' ? 'prueba' : 'activa',
                'fecha_inicio' => now(), 'fecha_fin' => now()->addMonth(),
                'precio' => $plan?->precio_mensual ?? 0,
            ]);

            // Usuario administrador de la academia
            User::create([
                'name' => $data['admin_nombre'], 'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'rol' => 'admin', 'academia_id' => $academia->id,
            ]);
        });

        return redirect()->route('superadmin.academias.index')->with('ok', 'Academia creada con su administrador.');
    }

    public function show(Academia $academia)
    {
        $academia->load('plan', 'suscripciones.plan', 'usuarios');
        $stats = [
            'estudiantes' => Estudiante::where('academia_id', $academia->id)->count(),
            'usuarios' => $academia->usuarios->count(),
        ];
        return view('superadmin.academias.show', compact('academia', 'stats'));
    }

    public function edit(Academia $academia)
    {
        return view('superadmin.academias.edit', ['academia' => $academia, 'planes' => Plan::where('activo', true)->get()]);
    }

    public function update(Request $request, Academia $academia)
    {
        $data = $this->validar($request, false);
        $academia->update($data);
        // sincroniza la suscripción más reciente con el plan/estado
        if ($sub = $academia->suscripciones()->latest()->first()) {
            $plan = $data['plan_id'] ? Plan::find($data['plan_id']) : null;
            $sub->update([
                'plan_id' => $plan?->id,
                'estado' => $data['estado'] === 'prueba' ? 'prueba' : ($data['estado'] === 'suspendida' ? 'cancelada' : 'activa'),
                'precio' => $plan?->precio_mensual ?? $sub->precio,
            ]);
        }
        return redirect()->route('superadmin.academias.index')->with('ok', 'Academia actualizada.');
    }

    public function destroy(Academia $academia)
    {
        $academia->delete();
        return redirect()->route('superadmin.academias.index')->with('ok', 'Academia eliminada con todos sus datos.');
    }

    /** El super admin "entra" a una academia para administrarla. */
    public function entrar(Request $request, Academia $academia)
    {
        $request->session()->put('admin_academia_id', $academia->id);
        return redirect()->route('dashboard')->with('ok', "Estás administrando: {$academia->nombre_academia}");
    }

    public function salir(Request $request)
    {
        $request->session()->forget('admin_academia_id');
        return redirect()->route('superadmin.panel')->with('ok', 'Volviste al panel de la plataforma.');
    }

    private function validar(Request $request, bool $conAdmin): array
    {
        $reglas = [
            'nombre_academia' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'moneda' => ['required', 'string', 'max:10'],
            'periodo_actual' => ['required', 'string', 'max:30'],
            'plan_id' => ['nullable', 'exists:planes,id'],
            'estado' => ['required', 'in:activa,prueba,suspendida'],
        ];
        if ($conAdmin) {
            $reglas += [
                'admin_nombre' => ['required', 'string', 'max:120'],
                'admin_email' => ['required', 'email', 'max:150', 'unique:users,email'],
                'admin_password' => ['required', 'string', 'min:6'],
            ];
        }
        return $request->validate($reglas);
    }
}
