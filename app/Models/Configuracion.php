<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Configuracion extends Model
{
    protected $table = 'configuracions';

    protected $fillable = [
        'nombre_academia', 'eslogan', 'ruc_nit', 'direccion', 'telefono',
        'email', 'sitio_web', 'moneda', 'periodo_actual', 'logo',
    ];

    /**
     * Devuelve la (única) fila de configuración, creándola si no existe.
     * Se cachea durante el ciclo de la petición.
     */
    public static function actual(): self
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        if (! Schema::hasTable('configuracions')) {
            return new self(['nombre_academia' => 'AcademiaPro', 'moneda' => 'Bs', 'periodo_actual' => date('Y')]);
        }

        return $cache = static::firstOrCreate(
            ['id' => 1],
            ['nombre_academia' => 'AcademiaPro', 'moneda' => 'Bs', 'periodo_actual' => date('Y')]
        );
    }
}
