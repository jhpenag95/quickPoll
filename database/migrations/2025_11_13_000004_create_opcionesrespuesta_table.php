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
        Schema::create('opcionesrespuesta', function (Blueprint $table) {
            $table->bigIncrements('idOpcion');
            $table->string('textoOpcion');
            $table->string('valor')->nullable();
            $table->integer('orden')->default(0);

            //Una opcion respuesta pertenece a una pregunta
            $table->unsignedBigInteger('idPregunta');
            $table->timestamps();
            $table->index(['idPregunta']);
            $table->index(['orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcionesrespuesta');
    }
};
