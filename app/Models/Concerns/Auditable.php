<?php

namespace App\Models\Concerns;

use App\Models\Bitacora;

/**
 * Registra automáticamente en la bitácora las altas, cambios y bajas del modelo.
 */
trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(fn ($model) => Bitacora::registrar('creó', $model));
        static::updated(fn ($model) => Bitacora::registrar('actualizó', $model));
        static::deleted(fn ($model) => Bitacora::registrar('eliminó', $model));
    }
}
