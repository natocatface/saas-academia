<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\MiPlanController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DocentePanelController;
use App\Http\Controllers\SuperAdmin\PanelController;
use App\Http\Controllers\SuperAdmin\AcademiaController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SuscripcionController;
use App\Http\Controllers\SuperAdmin\FacturaController;

// ---------- Público ----------
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : app(\App\Http\Controllers\LandingController::class)->index();
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
    Route::get('/registrar', [RegistroController::class, 'show'])->name('registro');
    Route::post('/registrar', [RegistroController::class, 'store'])->name('registro.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ---------- Aplicación (sesión + tenant) ----------
Route::middleware(['auth', 'tenant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil (cualquier usuario autenticado)
    Route::get('perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Portal del estudiante
    Route::get('portal', [PortalController::class, 'index'])->middleware('role:estudiante')->name('portal.index');

    // Panel del docente
    Route::middleware('role:docente')->prefix('docente')->name('docente.')->group(function () {
        Route::get('/', [DocentePanelController::class, 'index'])->name('index');
        Route::get('curso/{curso}/asistencia', [DocentePanelController::class, 'asistencia'])->name('asistencia');
        Route::post('curso/{curso}/asistencia', [DocentePanelController::class, 'guardarAsistencia'])->name('asistencia.guardar');
        Route::get('curso/{curso}/notas', [DocentePanelController::class, 'notas'])->name('notas');
        Route::post('curso/{curso}/notas', [DocentePanelController::class, 'guardarNota'])->name('notas.guardar');
    });

    // Pagos en línea (checkout simulado)
    Route::get('checkout/pago/{pago}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout/pago/{pago}', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::get('checkout/factura/{factura}', [CheckoutController::class, 'showFactura'])->name('checkout.factura');
        Route::post('checkout/factura/{factura}', [CheckoutController::class, 'processFactura'])->name('checkout.facturaPay');
    });

    // Módulos de la academia (protegidos por permiso de módulo)
    Route::resource('estudiantes', EstudianteController::class)->middleware('permiso:estudiantes');
    Route::resource('matriculas', MatriculaController::class)->middleware('permiso:matriculas');
    Route::resource('cursos', CursoController::class)->middleware('permiso:cursos');
    Route::resource('docentes', DocenteController::class)->middleware('permiso:docentes');
    Route::resource('asistencias', AsistenciaController::class)->middleware('permiso:asistencias');
    Route::resource('calificaciones', CalificacionController::class)->middleware('permiso:calificaciones');

    Route::middleware('permiso:pagos')->group(function () {
        Route::resource('pagos', PagoController::class);
        Route::patch('pagos/{pago}/marcar-pagado', [PagoController::class, 'marcarPagado'])->name('pagos.marcarPagado');
        Route::get('pagos/{pago}/recibo', [PagoController::class, 'recibo'])->name('pagos.recibo');
    });

    Route::middleware('permiso:reportes')->group(function () {
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('reportes/imprimir', [ReporteController::class, 'imprimir'])->name('reportes.imprimir');
        Route::get('reportes/exportar/{tipo}', [ReporteController::class, 'exportar'])->name('reportes.exportar');
    });

    // Bitácora: admin de academia o super admin
    Route::get('bitacora', [BitacoraController::class, 'index'])->middleware('role:admin,superadmin')->name('bitacora.index');

    // Administración de la academia (sólo admin / super admin)
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except('show');
        Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);
        Route::get('configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
        Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
        Route::get('mi-plan', [MiPlanController::class, 'index'])->name('miplan.index');
    });

    // ---------- Panel Super Admin (plataforma) ----------
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/', [PanelController::class, 'index'])->name('panel');
        Route::get('academias/{academia}/entrar', [AcademiaController::class, 'entrar'])->name('academias.entrar');
        Route::get('salir-academia', [AcademiaController::class, 'salir'])->name('academias.salir');
        Route::resource('academias', AcademiaController::class);
        Route::resource('planes', PlanController::class)->except('show');
        Route::get('suscripciones', [SuscripcionController::class, 'index'])->name('suscripciones.index');
        Route::get('suscripciones/{suscripcion}/edit', [SuscripcionController::class, 'edit'])->name('suscripciones.edit');
        Route::put('suscripciones/{suscripcion}', [SuscripcionController::class, 'update'])->name('suscripciones.update');

        Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
        Route::post('facturas/generar-mes', [FacturaController::class, 'generarMes'])->name('facturas.generarMes');
        Route::patch('facturas/{factura}/pagada', [FacturaController::class, 'marcarPagada'])->name('facturas.marcarPagada');
        Route::get('facturas/{factura}/recibo', [FacturaController::class, 'recibo'])->name('facturas.recibo');
        Route::delete('facturas/{factura}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    });
});
