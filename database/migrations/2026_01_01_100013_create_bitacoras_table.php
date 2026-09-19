<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('academia_id')->nullable()->index();
            $table->string('accion', 30);       // creó / actualizó / eliminó / ingresó / salió
            $table->string('modulo', 50)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void { Schema::dropIfExists('bitacoras'); }
};
