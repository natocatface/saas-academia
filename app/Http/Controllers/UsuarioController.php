<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public const ROLES = [
        'admin' => 'Administrador',
        'secretaria' => 'Secretaría',
        'docente' => 'Docente',
    ];

    /** Academia actual (la del admin, o la que el super admin está administrando). */
    private function academiaId(): ?int
    {
        return app()->bound('academia_actual_id') ? app('academia_actual_id') : auth()->user()->academia_id;
    }

    public function index(Request $request)
    {
        $q = $request->input('q');
        $usuarios = User::where('rol', '!=', 'superadmin')
            ->when($this->academiaId(), fn($x) => $x->where('academia_id', $this->academiaId()))
            ->when($q, fn($x) => $x->where(fn($w) => $w
                ->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%")))
            ->latest()->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios', 'q'));
    }

    public function create()
    {
        return view('usuarios.create', ['usuario' => new User(['rol' => 'secretaria']), 'roles' => self::ROLES]);
    }

    public function store(Request $request)
    {
        if (\App\Support\LimitePlan::alcanzado('usuarios')) {
            return back()->withInput()->with('error', 'Alcanzaste el límite de usuarios de tu plan. Mejora tu plan para agregar más.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'rol' => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['academia_id'] = $this->academiaId();
        User::create($data);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', ['usuario' => $usuario, 'roles' => self::ROLES]);
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rol' => ['required', Rule::in(array_keys(self::ROLES))],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (! empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }
        unset($data['password']);
        $usuario->fill($data)->save();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado.');
    }
}
