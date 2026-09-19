<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $table->string('concepto');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'qr', 'otro'])->default('efectivo');
            $table->date('fecha_pago')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['pagado', 'pendiente', 'anulado'])->default('pendiente');
            $table->string('referencia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('pagos'); }
};
