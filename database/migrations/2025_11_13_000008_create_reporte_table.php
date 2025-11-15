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
        Schema::create('reporte', function (Blueprint $table) {
            $table->bigIncrements('idReporte');

            //Un reporte pertenece a una encuesta
            $table->unsignedBigInteger('idEncuesta');
            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');

            //Un reporte pertenece a un usuario
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->dateTime('fechaGeneracion');
            $table->string('formato');
            $table->string('rutaArchivo');
            $table->timestamps();
            $table->index(['idEncuesta']);
            $table->index(['user_id']);
            $table->index(['fechaGeneracion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte');
    }
};
