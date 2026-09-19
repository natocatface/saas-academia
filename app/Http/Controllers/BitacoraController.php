<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $modulo = $request->input('modulo');
        $accion = $request->input('accion');

        $query = Bitacora::with('usuario')->latest('created_at');

        // El admin de una academia sólo ve su bitácora; el super admin ve todo.
        if (! $user->esSuperAdmin()) {
            $query->where('academia_id', $user->academia_id);
        }

        $registros = $query
            ->when($modulo, fn($x) => $x->where('modulo', $modulo))
            ->when($accion, fn($x) => $x->where('accion', $accion))
            ->paginate(20)->withQueryString();

        $modulos = Bitacora::query()
            ->when(! $user->esSuperAdmin(), fn($x) => $x->where('academia_id', $user->academia_id))
            ->select('modulo')->distinct()->pluck('modulo')->filter()->values();

        $layout = ($user->esSuperAdmin() && ! session('admin_academia_id')) ? 'layouts.superadmin' : 'layouts.app';

        return view('bitacora.index', compact('registros', 'modulo', 'accion', 'modulos', 'layout'));
    }
}
