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
        Schema::create('respuesta_encuesta', function (Blueprint $table) {
            $table->bigIncrements('idRespuesta');
            $table->dateTime('fechaRespuesta');
            $table->string('canalRespuesta');
            $table->string('ipUsuario');
            $table->boolean('completada');

            //Una respuesta pertenece a una encuesta
            $table->unsignedBigInteger('idEncuesta');
            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');

            $table->timestamps();
            $table->index(['idEncuesta']);
            $table->index(['fechaRespuesta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuesta_encuesta');
    }
};
