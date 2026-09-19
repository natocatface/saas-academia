<?php

namespace App\Console\Commands;

use App\Models\Suscripcion;
use App\Models\Estudiante;
use App\Models\Academia;
use App\Models\Pago;
use App\Models\User;
use App\Mail\SuscripcionPorVencer;
use App\Mail\PagoPendienteRecordatorio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarAvisos extends Command
{
    protected $signature = 'academia:avisos {--solo=ambos : vencimientos|pendientes|ambos}';
    protected $description = 'Envía avisos de vencimiento de suscripción y recordatorios de pagos pendientes';

    public function handle(): int
    {
        $solo = $this->option('solo');

        if (in_array($solo, ['vencimientos', 'ambos'], true)) {
            $this->avisosVencimiento();
        }
        if (in_array($solo, ['pendientes', 'ambos'], true)) {
            $this->avisosPagos();
        }

        $this->info('Avisos procesados correctamente.');
        return self::SUCCESS;
    }

    private function avisosVencimiento(): void
    {
        $subs = Suscripcion::with('academia')
            ->whereIn('estado', ['activa', 'prueba'])
            ->whereNotNull('fecha_fin')
            ->whereBetween('fecha_fin', [now()->startOfDay(), now()->addDays(5)->endOfDay()])
            ->get();

        $n = 0;
        foreach ($subs as $s) {
            $admin = User::where('academia_id', $s->academia_id)->where('rol', 'admin')->first();
            if ($admin && $s->academia) {
                $dias = max(0, (int) now()->startOfDay()->diffInDays($s->fecha_fin, false));
                Mail::to($admin->email)->send(new SuscripcionPorVencer($s->academia, $dias));
                $n++;
            }
        }
        $this->line("Avisos de vencimiento enviados: {$n}");
    }

    private function avisosPagos(): void
    {
        $deudas = Pago::where('estado', 'pendiente')
            ->selectRaw('estudiante_id, academia_id, SUM(monto) as deuda')
            ->groupBy('estudiante_id', 'academia_id')
            ->take(100)->get();

        $n = 0;
        foreach ($deudas as $d) {
            $est = Estudiante::find($d->estudiante_id);
            if ($est && $est->email) {
                $aca = Academia::find($d->academia_id);
                Mail::to($est->email)->send(new PagoPendienteRecordatorio($est, (float) $d->deuda, $aca->nombre_academia ?? ''));
                $n++;
            }
        }
        $this->line("Recordatorios de pago enviados: {$n}");
    }
}
