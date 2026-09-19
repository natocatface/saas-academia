<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'asistencias';

    protected $fillable = [
        'academia_id',
        'estudiante_id', 'curso_id', 'fecha', 'estado', 'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function curso()      { return $this->belongsTo(Curso::class); }
}
