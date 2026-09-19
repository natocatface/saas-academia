<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('academia_id')->constrained('academias')->cascadeOnDelete();
            $table->foreignId('suscripcion_id')->nullable()->constrained('suscripciones')->nullOnDelete();
            $table->string('periodo');                  // p.ej. "Junio 2026"
            $table->decimal('monto', 10, 2)->default(0);
            $table->enum('estado', ['pagada', 'pendiente', 'anulada'])->default('pendiente');
            $table->date('fecha_emision');
            $table->date('fecha_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('facturas'); }
};
