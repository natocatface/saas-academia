<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'avatar',
        'academia_id',
        'estudiante_id',
        'docente_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function academia()
    {
        return $this->belongsTo(Academia::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function esEstudiante(): bool
    {
        return $this->rol === 'estudiante';
    }

    public function esDocente(): bool
    {
        return $this->rol === 'docente';
    }

    public function iniciales(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));
        $ini = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $ini .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $ini ?: 'U';
    }

    public function esSuperAdmin(): bool
    {
        return $this->rol === 'superadmin';
    }

    public function esAdmin(): bool
    {
        return in_array($this->rol, ['admin', 'superadmin'], true);
    }

    /** ¿El rol del usuario puede acceder al módulo indicado? */
    public function puede(string $modulo): bool
    {
        if ($this->esSuperAdmin()) {
            return true;
        }
        $permisos = Role::permisosDe($this->rol);
        return in_array('*', $permisos, true) || in_array($modulo, $permisos, true);
    }
}
