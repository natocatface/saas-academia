<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'estudiantes';

    protected $fillable = [
        'academia_id',
        'codigo', 'nombres', 'apellidos', 'documento', 'email', 'telefono',
        'fecha_nacimiento', 'genero', 'direccion', 'apoderado', 'telefono_apoderado',
        'estado', 'foto',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function matriculas() { return $this->hasMany(Matricula::class); }
    public function pagos()      { return $this->hasMany(Pago::class); }
    public function asistencias(){ return $this->hasMany(Asistencia::class); }
    public function calificaciones() { return $this->hasMany(Calificacion::class); }
}
