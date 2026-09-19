<?php

namespace App\Models\Concerns;

use App\Models\Academia;
use Illuminate\Database\Eloquent\Builder;

/**
 * Aísla los datos por academia (multi-tenant).
 * Filtra automáticamente por la academia actual y asigna academia_id al crear.
 */
trait Tenantable
{
    protected static function bootTenantable(): void
    {
        static::addGlobalScope('academia', function (Builder $builder) {
            $id = app()->bound('academia_actual_id') ? app('academia_actual_id') : null;
            if ($id) {
                $builder->where($builder->getModel()->getTable() . '.academia_id', $id);
            }
        });

        static::creating(function ($model) {
            if (empty($model->academia_id) && app()->bound('academia_actual_id') && app('academia_actual_id')) {
                $model->academia_id = app('academia_actual_id');
            }
        });
    }

    public function academia()
    {
        return $this->belongsTo(Academia::class);
    }

    /** Consultar sin el filtro de academia (uso del super admin). */
    public function scopeSinTenant(Builder $query): Builder
    {
        return $query->withoutGlobalScope('academia');
    }
}
