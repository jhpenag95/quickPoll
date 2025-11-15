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
        Schema::create('detalle_respuesta', function (Blueprint $table) {
            $table->bigIncrements('idDetalle');
            $table->string('textoRespuesta');
            $table->integer('valorNumerico');
            
            //Un detalle respuesta pertenece a una respuesta
            $table->unsignedBigInteger('idRespuesta');
            $table->foreign('idRespuesta')->references('idRespuesta')->on('respuesta_encuesta')->onDelete('cascade');

            //Un detalle respuesta pertenece a una pregunta
            $table->unsignedBigInteger('idPregunta');
            $table->foreign('idPregunta')->references('idPregunta')->on('preguntas')->onDelete('cascade');
            
            //Un detalle respuesta pertenece a una opcion respuesta
            $table->unsignedBigInteger('idOpcionRespuesta');
            $table->foreign('idOpcionRespuesta')->references('idOpcionRespuesta')->on('opcionesrespuesta')->onDelete('cascade');
            $table->timestamps();
            $table->index(['idRespuesta']);
            $table->index(['idPregunta']);
            $table->index(['idOpcionRespuesta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_respuesta');
    }
};
