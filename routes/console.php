<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Avisos automáticos: vencimiento de suscripciones (diario) y pagos pendientes (semanal)
Schedule::command('academia:avisos --solo=vencimientos')->dailyAt('08:00');
Schedule::command('academia:avisos --solo=pendientes')->weeklyOn(1, '09:00');
