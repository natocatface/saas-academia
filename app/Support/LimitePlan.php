<?php

namespace App\Support;

use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\User;

/**
 * Verifica los límites del plan de la academia actual.
 * Un límite de 0 significa "ilimitado".
 */
class LimitePlan
{
    private static function academia()
    {
        return app()->bound('academia_actual') ? app('academia_actual') : null;
    }

    public static function limite(string $recurso): int
    {
        $a = self::academia();
        if (! $a || ! $a->plan) {
            return 0; // sin plan => sin límite
        }
        return (int) match ($recurso) {
            'estudiantes' => $a->plan->limite_estudiantes,
            'usuarios' => $a->plan->limite_usuarios,
            'cursos' => $a->plan->limite_cursos,
            default => 0,
        };
    }

    public static function uso(string $recurso): int
    {
        $a = self::academia();
        return (int) match ($recurso) {
            'estudiantes' => Estudiante::count(),
            'cursos' => Curso::count(),
            'usuarios' => $a ? User::where('academia_id', $a->id)->where('rol', '!=', 'superadmin')->count() : 0,
            default => 0,
        };
    }

    /** ¿Se alcanzó el tope del plan para este recurso? */
    public static function alcanzado(string $recurso): bool
    {
        $limite = self::limite($recurso);
        if ($limite <= 0) {
            return false; // ilimitado
        }
        return self::uso($recurso) >= $limite;
    }

    public static function porcentaje(string $recurso): int
    {
        $limite = self::limite($recurso);
        if ($limite <= 0) {
            return 0;
        }
        return min(100, (int) round(self::uso($recurso) / $limite * 100));
    }
}
