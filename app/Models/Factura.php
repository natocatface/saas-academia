<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use Auditable;

    protected $table = 'facturas';

    protected $fillable = [
        'numero', 'academia_id', 'suscripcion_id', 'periodo', 'monto',
        'estado', 'fecha_emision', 'fecha_pago', 'metodo_pago',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_emision' => 'date',
        'fecha_pago' => 'date',
    ];

    public function academia()   { return $this->belongsTo(Academia::class); }
    public function suscripcion() { return $this->belongsTo(Suscripcion::class); }

    public static function siguienteNumero(): string
    {
        $n = (int) (static::max('id') ?? 0) + 1;
        return 'FAC-' . date('Y') . '-' . str_pad($n, 5, '0', STR_PAD_LEFT);
    }
}
