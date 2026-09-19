<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'docentes';

    protected $fillable = [
        'academia_id',
        'codigo', 'nombres', 'apellidos', 'documento', 'email', 'telefono',
        'especialidad', 'titulo', 'fecha_contratacion', 'estado',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function cursos() { return $this->hasMany(Curso::class); }
}
