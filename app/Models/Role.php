<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['slug', 'nombre', 'permisos', 'es_sistema'];

    protected $casts = [
        'permisos' => 'array',
        'es_sistema' => 'boolean',
    ];

    /** Devuelve los permisos (array de módulos) de un rol, cacheado. */
    public static function permisosDe(string $slug): array
    {
        $roles = Cache::remember('roles_permisos', 300, function () {
            return static::pluck('permisos', 'slug')->toArray();
        });

        $permisos = $roles[$slug] ?? [];
        if (is_string($permisos)) {
            $permisos = json_decode($permisos, true) ?: [];
        }
        return $permisos;
    }

    public static function limpiarCache(): void
    {
        Cache::forget('roles_permisos');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::limpiarCache());
        static::deleted(fn () => static::limpiarCache());
    }
}
