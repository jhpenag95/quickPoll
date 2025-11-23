<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuesta_visitas', function (Blueprint $table) {
            $table->bigIncrements('idVisita');
            $table->unsignedBigInteger('idEncuesta');
            $table->string('ipUsuario', 45);
            $table->string('userAgent')->nullable();
            $table->timestamps();

            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');
            $table->index(['idEncuesta']);
            $table->index(['ipUsuario']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuesta_visitas');
    }
};