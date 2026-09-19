<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->forget('admin_academia_id');

            Bitacora::registrar('ingresó', null, 'Inició sesión en el sistema');

            $u = Auth::user();
            if ($u->esSuperAdmin()) {
                return redirect()->route('superadmin.panel');
            }
            if ($u->esEstudiante()) {
                return redirect()->route('portal.index');
            }
            if ($u->esDocente()) {
                return redirect()->route('docente.index');
            }
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Bitacora::registrar('salió', null, 'Cerró sesión');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
