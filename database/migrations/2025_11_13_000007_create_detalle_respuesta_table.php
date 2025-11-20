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
            $table->string('textoRespuesta')->nullable();
            $table->integer('valorNumerico')->nullable();
            
            //Un detalle respuesta pertenece a una respuesta
            $table->unsignedBigInteger('idRespuesta');
            $table->foreign('idRespuesta')->references('idRespuesta')->on('respuesta_encuesta')->onDelete('cascade');

            //Un detalle respuesta pertenece a una pregunta
            $table->unsignedBigInteger('idPregunta');
            $table->foreign('idPregunta')->references('idPregunta')->on('preguntas')->onDelete('cascade');
            
            //Un detalle respuesta pertenece a una opcion respuesta
            $table->unsignedBigInteger('idOpcion')->nullable();  
            $table->foreign('idOpcion')->references('idOpcion')->on('opcionesrespuesta')->onDelete('set null');
                
            $table->timestamps();
            $table->index(['idRespuesta']);
            $table->index(['idPregunta']);
            $table->index(['idOpcion']);
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
