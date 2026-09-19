<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'matriculas';

    protected $fillable = [
        'academia_id',
        'codigo', 'estudiante_id', 'curso_id', 'periodo', 'fecha_inicio',
        'monto_matricula', 'descuento', 'estado', 'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'monto_matricula' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function curso()      { return $this->belongsTo(Curso::class); }
    public function pagos()      { return $this->hasMany(Pago::class); }
}
