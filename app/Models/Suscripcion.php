<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use Auditable;

    protected $table = 'suscripciones';

    protected $fillable = [
        'academia_id', 'plan_id', 'estado', 'fecha_inicio', 'fecha_fin', 'precio',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'precio' => 'decimal:2',
    ];

    public function academia() { return $this->belongsTo(Academia::class); }
    public function plan()      { return $this->belongsTo(Plan::class); }

    public function estaVigente(): bool
    {
        return in_array($this->estado, ['activa', 'prueba'], true)
            && (! $this->fecha_fin || $this->fecha_fin->isFuture() || $this->fecha_fin->isToday());
    }
}
