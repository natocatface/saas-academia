<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'calificaciones';

    protected $fillable = [
        'academia_id',
        'estudiante_id', 'curso_id', 'evaluacion', 'nota', 'periodo', 'fecha', 'observacion',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha' => 'date',
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function curso()      { return $this->belongsTo(Curso::class); }
}
