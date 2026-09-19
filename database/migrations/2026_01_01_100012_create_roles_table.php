<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->text('permisos')->nullable(); // JSON: lista de claves de módulo permitidas
            $table->boolean('es_sistema')->default(false);
            $table->timestamps();
        });

        $todos = ['dashboard','estudiantes','matriculas','cursos','docentes','pagos','asistencias','calificaciones','reportes','usuarios','configuracion'];

        DB::table('roles')->insert([
            ['slug' => 'superadmin', 'nombre' => 'Super Administrador', 'es_sistema' => true,
             'permisos' => json_encode(['*']), 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'admin', 'nombre' => 'Administrador', 'es_sistema' => true,
             'permisos' => json_encode($todos), 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'secretaria', 'nombre' => 'Secretaría', 'es_sistema' => true,
             'permisos' => json_encode(['dashboard','estudiantes','matriculas','cursos','docentes','pagos','reportes']),
             'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'docente', 'nombre' => 'Docente', 'es_sistema' => true,
             'permisos' => json_encode(['dashboard','asistencias','calificaciones','cursos']),
             'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'estudiante', 'nombre' => 'Estudiante', 'es_sistema' => true,
             'permisos' => json_encode([]),
             'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void { Schema::dropIfExists('roles'); }
};
