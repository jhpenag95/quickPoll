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
        Schema::create('integracion_whatsapp', function (Blueprint $table) {
            $table->bigIncrements('idIntegracion');
            $table->string('numeroWhatsapp');
            $table->string('tokenAPI');
            $table->boolean('activo')->default(true);

            //UNA INTEGRACION PERTENECE A UNA ENCUESTA
            $table->unsignedBigInteger('idEncuesta');
            $table->foreign('idEncuesta')->references('idEncuesta')->on('encuestas')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['idEncuesta']);
            $table->index(['idEncuesta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integracion_whatsapp');
    }
};
