<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Models\User;
use App\Mail\BienvenidaAcademia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistroController extends Controller
{
    public function show(Request $request)
    {
        $planes = Plan::where('activo', true)->orderBy('precio_mensual')->get();
        $planSel = $request->query('plan');
        return view('public.registro', compact('planes', 'planSel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_academia' => ['required', 'string', 'max:120'],
            'plan_id' => ['required', 'exists:planes,id'],
            'admin_nombre' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [], ['admin_password' => 'contraseña']);

        $user = DB::transaction(function () use ($data) {
            $plan = Plan::find($data['plan_id']);

            $academia = Academia::create([
                'nombre_academia' => $data['nombre_academia'],
                'email' => $data['admin_email'],
                'moneda' => 'Bs',
                'periodo_actual' => date('Y'),
                'plan_id' => $plan->id,
                'estado' => 'prueba',
                'fecha_registro' => now(),
            ]);

            Suscripcion::create([
                'academia_id' => $academia->id,
                'plan_id' => $plan->id,
                'estado' => 'prueba',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addDays(15),   // 15 días de prueba
                'precio' => $plan->precio_mensual,
            ]);

            return User::create([
                'name' => $data['admin_nombre'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'rol' => 'admin',
                'academia_id' => $academia->id,
            ]);
        });

        // Correo de bienvenida (con MAIL_MAILER=log queda en storage/logs)
        try {
            Mail::to($user->email)->send(new BienvenidaAcademia($user));
        } catch (\Throwable $e) {
            // no interrumpir el registro si el correo falla
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('ok', '¡Bienvenido! Tu academia se creó con 15 días de prueba.');
    }
}
