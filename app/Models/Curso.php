<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'cursos';

    protected $fillable = [
        'academia_id',
        'codigo', 'nombre', 'descripcion', 'docente_id', 'nivel', 'modalidad',
        'horario', 'aula', 'cupo_maximo', 'duracion_meses', 'costo_matricula',
        'costo_mensual', 'estado',
    ];

    protected $casts = [
        'costo_matricula' => 'decimal:2',
        'costo_mensual' => 'decimal:2',
    ];

    public function docente()   { return $this->belongsTo(Docente::class); }
    public function matriculas(){ return $this->hasMany(Matricula::class); }

    public function getCuposDisponiblesAttribute(): int
    {
        return max(0, $this->cupo_maximo - $this->matriculas()->where('estado', 'activa')->count());
    }
}
