<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory, Tenantable, Auditable;

    protected $table = 'pagos';

    protected $fillable = [
        'academia_id',
        'codigo', 'estudiante_id', 'matricula_id', 'concepto', 'monto',
        'metodo_pago', 'fecha_pago', 'fecha_vencimiento', 'estado', 'referencia',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function matricula()  { return $this->belongsTo(Matricula::class); }
}
