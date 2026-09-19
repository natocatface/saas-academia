<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function edit()
    {
        return view('perfil.edit', ['usuario' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $usuario = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'password_actual' => ['nullable', 'required_with:password', 'string'],
        ]);

        if (! empty($data['password'])) {
            if (! Hash::check($request->input('password_actual', ''), $usuario->password)) {
                return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
            }
            $usuario->password = Hash::make($data['password']);
        }

        if ($request->hasFile('avatar')) {
            $usuario->avatar = $request->file('avatar')->store('avatares', 'public');
        }

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        $usuario->save();

        return back()->with('ok', 'Perfil actualizado correctamente.');
    }
}
