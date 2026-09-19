<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('documento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F', 'Otro'])->nullable();
            $table->string('direccion')->nullable();
            $table->string('apoderado')->nullable();
            $table->string('telefono_apoderado')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'egresado'])->default('activo');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('estudiantes'); }
};
