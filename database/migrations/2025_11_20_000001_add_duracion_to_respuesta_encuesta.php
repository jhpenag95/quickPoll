<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('respuesta_encuesta', function (Blueprint $table) {
            if (!Schema::hasColumn('respuesta_encuesta', 'duracionSegundos')) {
                $table->integer('duracionSegundos')->nullable()->after('ipUsuario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('respuesta_encuesta', function (Blueprint $table) {
            if (Schema::hasColumn('respuesta_encuesta', 'duracionSegundos')) {
                $table->dropColumn('duracionSegundos');
            }
        });
    }
};