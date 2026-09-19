<?php

namespace App\Providers;

use App\Models\Academia;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Comparte la academia actual (tenant) con todas las vistas como $academia.
        View::composer('*', function ($view) {
            $academia = app()->bound('academia_actual') ? app('academia_actual') : null;

            if (! $academia) {
                try {
                    $academia = Academia::query()->first();
                } catch (\Throwable $e) {
                    $academia = null;
                }
            }

            if (! $academia) {
                $academia = new Academia([
                    'nombre_academia' => 'AcademiaPro',
                    'moneda' => 'Bs',
                    'periodo_actual' => date('Y'),
                ]);
            }

            $view->with('academia', $academia);
        });
    }
}
