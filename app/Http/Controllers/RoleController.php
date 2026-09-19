<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public const MODULOS = [
        'dashboard' => 'Dashboard',
        'estudiantes' => 'Estudiantes',
        'matriculas' => 'Matrículas',
        'cursos' => 'Cursos',
        'docentes' => 'Docentes',
        'pagos' => 'Pagos',
        'asistencias' => 'Asistencia',
        'calificaciones' => 'Calificaciones',
        'reportes' => 'Reportes',
        'usuarios' => 'Usuarios',
        'configuracion' => 'Configuración',
    ];

    public function index()
    {
        $roles = Role::orderBy('id')->get();
        return view('roles.index', ['roles' => $roles, 'modulos' => self::MODULOS]);
    }

    public function edit(Role $role)
    {
        return view('roles.edit', ['role' => $role, 'modulos' => self::MODULOS]);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->slug === 'superadmin') {
            return back()->with('error', 'El rol Super Administrador no se puede modificar.');
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:60'],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['string'],
        ]);

        $role->update([
            'nombre' => $data['nombre'],
            'permisos' => array_values(array_intersect($data['permisos'] ?? [], array_keys(self::MODULOS))),
        ]);

        return redirect()->route('roles.index')->with('ok', "Permisos del rol {$role->nombre} actualizados.");
    }
}
