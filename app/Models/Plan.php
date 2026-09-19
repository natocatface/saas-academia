<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use Auditable;

    protected $table = 'planes';

    protected $fillable = [
        'nombre', 'slug', 'precio_mensual', 'limite_estudiantes',
        'limite_usuarios', 'limite_cursos', 'caracteristicas', 'color', 'activo',
    ];

    protected $casts = [
        'precio_mensual' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function academias()
    {
        return $this->hasMany(Academia::class);
    }

    public function getListaCaracteristicasAttribute(): array
    {
        return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $this->caracteristicas)));
    }

    public function limiteTexto(int $valor): string
    {
        return $valor === 0 ? 'Ilimitado' : (string) $valor;
    }
}
