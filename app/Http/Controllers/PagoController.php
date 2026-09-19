<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Estudiante;
use App\Models\Matricula;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');
        $pagos = Pago::with(['estudiante', 'matricula.curso'])
            ->when($q, fn($query) => $query
                ->where('codigo', 'like', "%$q%")
                ->orWhere('concepto', 'like', "%$q%")
                ->orWhereHas('estudiante', fn($e) => $e->where('nombres', 'like', "%$q%")->orWhere('apellidos', 'like', "%$q%")))
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->latest()->paginate(12)->withQueryString();

        $totalPagado = Pago::where('estado', 'pagado')->sum('monto');
        $totalPendiente = Pago::where('estado', 'pendiente')->sum('monto');

        return view('pagos.index', compact('pagos', 'q', 'estado', 'totalPagado', 'totalPendiente'));
    }

    public function create()
    {
        return view('pagos.create', [
            'pago' => new Pago(['fecha_pago' => date('Y-m-d')]),
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),
            'matriculas' => Matricula::with('curso')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['codigo'] = 'PAG-' . str_pad(Pago::withoutGlobalScope('academia')->max('id') + 1, 6, '0', STR_PAD_LEFT);
        Pago::create($data);
        return redirect()->route('pagos.index')->with('ok', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago)
    {
        $pago->load(['estudiante', 'matricula.curso']);
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        return view('pagos.edit', [
            'pago' => $pago,
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),
            'matriculas' => Matricula::with('curso')->latest()->get(),
        ]);
    }

    public function update(Request $request, Pago $pago)
    {
        $pago->update($this->validar($request));
        return redirect()->route('pagos.index')->with('ok', 'Pago actualizado.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('ok', 'Pago eliminado.');
    }

    public function marcarPagado(Pago $pago)
    {
        $pago->update(['estado' => 'pagado', 'fecha_pago' => now()]);
        return back()->with('ok', "Pago {$pago->codigo} marcado como pagado.");
    }

    public function recibo(Pago $pago)
    {
        $pago->load(['estudiante', 'matricula.curso']);
        return view('pagos.recibo', compact('pago'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'estudiante_id' => ['required', 'exists:estudiantes,id'],
            'matricula_id' => ['nullable', 'exists:matriculas,id'],
            'concepto' => ['required', 'string', 'max:150'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:efectivo,transferencia,tarjeta,qr,otro'],
            'fecha_pago' => ['nullable', 'date'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'estado' => ['required', 'in:pagado,pendiente,anulado'],
            'referencia' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
