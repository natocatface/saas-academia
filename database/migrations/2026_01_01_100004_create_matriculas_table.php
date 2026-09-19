<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('periodo');
            $table->date('fecha_inicio')->nullable();
            $table->decimal('monto_matricula', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->enum('estado', ['activa', 'retirada', 'finalizada', 'suspendida'])->default('activa');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('matriculas'); }
};
