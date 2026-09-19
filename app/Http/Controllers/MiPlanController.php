<?php

namespace App\Http\Controllers;

use App\Models\Factura;

class MiPlanController extends Controller
{
    public function index()
    {
        $academia = app()->bound('academia_actual') ? app('academia_actual') : null;
        abort_unless($academia, 404);

        $academia->load('plan', 'suscripcionActiva.plan');
        $facturas = Factura::where('academia_id', $academia->id)->latest('fecha_emision')->take(24)->get();

        return view('miplan.index', compact('academia', 'facturas'));
    }
}
