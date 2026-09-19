<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tablas = [
        'users', 'estudiantes', 'docentes', 'cursos',
        'matriculas', 'pagos', 'asistencias', 'calificaciones',
    ];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            if (! Schema::hasColumn($tabla, 'academia_id')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->unsignedBigInteger('academia_id')->nullable()->after('id')->index();
                });
            }
        }

        // Los registros existentes (datos previos) quedan asignados a la academia 1.
        foreach ($this->tablas as $tabla) {
            if ($tabla === 'users') {
                // Los usuarios sin rol superadmin se asignan a la academia 1; el superadmin queda en null.
                DB::table('users')->whereNull('academia_id')->where('rol', '!=', 'superadmin')->update(['academia_id' => 1]);
            } else {
                DB::table($tabla)->whereNull('academia_id')->update(['academia_id' => 1]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            if (Schema::hasColumn($tabla, 'academia_id')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->dropColumn('academia_id');
                });
            }
        }
    }
};
