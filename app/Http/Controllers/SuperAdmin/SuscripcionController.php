<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use App\Models\Plan;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');
        $suscripciones = Suscripcion::with('academia', 'plan')
            ->when($estado, fn($x) => $x->where('estado', $estado))
            ->latest()->paginate(12)->withQueryString();

        $resumen = [
            'activas' => Suscripcion::where('estado', 'activa')->count(),
            'prueba' => Suscripcion::where('estado', 'prueba')->count(),
            'vencidas' => Suscripcion::where('estado', 'vencida')->count(),
            'mrr' => Suscripcion::whereIn('estado', ['activa', 'prueba'])->sum('precio'),
        ];

        return view('superadmin.suscripciones.index', compact('suscripciones', 'estado', 'resumen'));
    }

    public function edit(Suscripcion $suscripcion)
    {
        return view('superadmin.suscripciones.edit', ['suscripcion' => $suscripcion, 'planes' => Plan::all()]);
    }

    public function update(Request $request, Suscripcion $suscripcion)
    {
        $data = $request->validate([
            'plan_id' => ['nullable', 'exists:planes,id'],
            'estado' => ['required', 'in:activa,prueba,vencida,cancelada'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);
        $suscripcion->update($data);

        // refleja el plan/estado en la academia
        if ($suscripcion->academia) {
            $suscripcion->academia->update([
                'plan_id' => $data['plan_id'] ?? null,
                'estado' => in_array($data['estado'], ['vencida', 'cancelada']) ? 'suspendida' : ($data['estado'] === 'prueba' ? 'prueba' : 'activa'),
            ]);
        }

        return redirect()->route('superadmin.suscripciones.index')->with('ok', 'Suscripción actualizada.');
    }
}
