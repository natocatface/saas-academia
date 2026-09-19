<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'estudiante_id')) {
                $table->unsignedBigInteger('estudiante_id')->nullable()->after('academia_id')->index();
            }
            if (! Schema::hasColumn('users', 'docente_id')) {
                $table->unsignedBigInteger('docente_id')->nullable()->after('estudiante_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['estudiante_id', 'docente_id']);
        });
    }
};
