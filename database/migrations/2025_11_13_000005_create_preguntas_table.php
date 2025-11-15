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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->bigIncrements('idPregunta');
            $table->string('textoPregunta');
            $table->string('tipoPregunta');
            $table->boolean('obligatoria');
            $table->integer('orden');
            //Una pregunta pertenece a una encuesta
            $table->unsignedBigInteger('idEncuesta');
            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');

            // Relación con detalle_respuesta se define desde detalle_respuesta (belongsTo pregunta)
            $table->timestamps();
            $table->softDeletes();
            $table->index(['idEncuesta']);
            $table->index(['orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
