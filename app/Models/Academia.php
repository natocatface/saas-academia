<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Academia extends Model
{
    use Auditable;

    protected $table = 'academias';

    protected $fillable = [
        'slug', 'nombre_academia', 'eslogan', 'ruc_nit', 'direccion', 'telefono',
        'email', 'sitio_web', 'moneda', 'periodo_actual', 'logo',
        'plan_id', 'estado', 'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($academia) {
            if (empty($academia->slug)) {
                $base = Str::slug($academia->nombre_academia ?: 'academia');
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . (++$i);
                }
                $academia->slug = $slug;
            }
            if (empty($academia->fecha_registro)) {
                $academia->fecha_registro = now();
            }
        });
    }

    public function plan()          { return $this->belongsTo(Plan::class); }
    public function suscripciones() { return $this->hasMany(Suscripcion::class); }
    public function usuarios()      { return $this->hasMany(User::class); }
    public function estudiantes()   { return $this->hasMany(Estudiante::class); }

    public function suscripcionActiva()
    {
        return $this->hasOne(Suscripcion::class)->latestOfMany();
    }

    /** Inicial para el avatar/logo de texto. */
    public function inicial(): string
    {
        return mb_strtoupper(mb_substr($this->nombre_academia ?? 'A', 0, 1));
    }
}
