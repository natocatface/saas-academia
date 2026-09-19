<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('docente_id')->nullable()->constrained('docentes')->nullOnDelete();
            $table->enum('nivel', ['basico', 'intermedio', 'avanzado'])->default('basico');
            $table->enum('modalidad', ['presencial', 'virtual', 'hibrido'])->default('presencial');
            $table->string('horario')->nullable();
            $table->string('aula')->nullable();
            $table->unsignedInteger('cupo_maximo')->default(30);
            $table->unsignedInteger('duracion_meses')->default(3);
            $table->decimal('costo_matricula', 10, 2)->default(0);
            $table->decimal('costo_mensual', 10, 2)->default(0);
            $table->enum('estado', ['activo', 'inactivo', 'finalizado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('cursos'); }
};
