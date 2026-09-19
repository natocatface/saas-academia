<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    private function academiaActual(): Academia
    {
        return app()->bound('academia_actual') ? app('academia_actual') : Academia::query()->firstOrFail();
    }

    public function edit()
    {
        $config = $this->academiaActual();
        return view('configuracion.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nombre_academia' => ['required', 'string', 'max:120'],
            'eslogan' => ['nullable', 'string', 'max:150'],
            'ruc_nit' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'sitio_web' => ['nullable', 'string', 'max:120'],
            'moneda' => ['required', 'string', 'max:10'],
            'periodo_actual' => ['required', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $config = $this->academiaActual();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $config->update($data);

        return back()->with('ok', 'Configuración actualizada correctamente.');
    }
}
