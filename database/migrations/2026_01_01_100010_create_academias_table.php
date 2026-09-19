<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academias', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre_academia');
            $table->string('eslogan')->nullable();
            $table->string('ruc_nit')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('moneda', 10)->default('Bs');
            $table->string('periodo_actual')->default('2026');
            $table->string('logo')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('planes')->nullOnDelete();
            $table->enum('estado', ['activa', 'prueba', 'suspendida'])->default('activa');
            $table->date('fecha_registro')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('academias'); }
};
