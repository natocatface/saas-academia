<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = ['created_at' => 'datetime'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Crea un registro de bitácora. Sólo registra si hay un usuario autenticado.
     */
    public static function registrar(string $accion, $modelo = null, ?string $descripcion = null): void
    {
        if (! auth()->check()) {
            return;
        }

        $modulo = $modelo ? class_basename($modelo) : null;

        if (! $descripcion && $modelo) {
            $nombre = $modelo->nombre_completo
                ?? $modelo->nombre
                ?? $modelo->codigo
                ?? $modelo->name
                ?? ('#' . $modelo->getKey());
            $descripcion = "{$modulo}: {$nombre}";
        }

        try {
            static::create([
                'user_id' => auth()->id(),
                'academia_id' => app()->bound('academia_actual_id')
                    ? app('academia_actual_id')
                    : (auth()->user()->academia_id ?? null),
                'accion' => $accion,
                'modulo' => $modulo,
                'descripcion' => $descripcion,
                'ip' => request()->ip(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // La auditoría nunca debe interrumpir la operación principal.
        }
    }
}
