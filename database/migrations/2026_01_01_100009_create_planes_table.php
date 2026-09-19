<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->decimal('precio_mensual', 10, 2)->default(0);
            $table->integer('limite_estudiantes')->default(0); // 0 = ilimitado
            $table->integer('limite_usuarios')->default(0);
            $table->integer('limite_cursos')->default(0);
            $table->text('caracteristicas')->nullable();
            $table->string('color', 20)->default('#1fbfe6');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::table('planes')->insert([
            ['nombre' => 'Básico', 'slug' => 'basico', 'precio_mensual' => 199, 'limite_estudiantes' => 100,
             'limite_usuarios' => 3, 'limite_cursos' => 10, 'color' => '#2fc28a',
             'caracteristicas' => "Hasta 100 estudiantes\nHasta 3 usuarios\n10 cursos\nReportes básicos",
             'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Profesional', 'slug' => 'profesional', 'precio_mensual' => 399, 'limite_estudiantes' => 500,
             'limite_usuarios' => 10, 'limite_cursos' => 50, 'color' => '#1fbfe6',
             'caracteristicas' => "Hasta 500 estudiantes\nHasta 10 usuarios\n50 cursos\nReportes avanzados\nRecibos PDF",
             'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Premium', 'slug' => 'premium', 'precio_mensual' => 799, 'limite_estudiantes' => 0,
             'limite_usuarios' => 0, 'limite_cursos' => 0, 'color' => '#fbb13c',
             'caracteristicas' => "Estudiantes ilimitados\nUsuarios ilimitados\nCursos ilimitados\nTodos los reportes\nSoporte prioritario",
             'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void { Schema::dropIfExists('planes'); }
};
