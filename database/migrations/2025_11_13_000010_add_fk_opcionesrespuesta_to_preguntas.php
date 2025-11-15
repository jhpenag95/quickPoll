<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opcionesrespuesta', function (Blueprint $table) {
            // Asegurar que la clave foránea se agregue después de existir 'preguntas'
            $table->foreign('idPregunta')
                ->references('idPregunta')
                ->on('preguntas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opcionesrespuesta', function (Blueprint $table) {
            $table->dropForeign(['idPregunta']);
        });
    }
};