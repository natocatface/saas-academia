<?php

namespace App\Http\Middleware;

use App\Models\Academia;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Determina la academia actual (tenant) y la deja disponible para el scoping
 * automático de los modelos y para el branding de las vistas.
 */
class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $academiaId = $user->esSuperAdmin()
                ? session('admin_academia_id')   // null si el super admin no está dentro de una academia
                : $user->academia_id;

            if ($academiaId) {
                $academia = Academia::find($academiaId);
                if ($academia) {
                    app()->instance('academia_actual_id', $academia->id);
                    app()->instance('academia_actual', $academia);
                }
            }
        }

        return $next($request);
    }
}
