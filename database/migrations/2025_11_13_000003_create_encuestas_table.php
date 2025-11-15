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
        Schema::create('encuestas', function (Blueprint $table) {
            $table->bigIncrements('idEncuesta');
            $table->string('nombre');
            $table->text('descripcion');
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->string('estado')->default('BORRADOR');
            $table->text('enlaceLargo');
            $table->string('enlaceCorto')->nullable();
            $table->text('codigoQR')->nullable();
            
            //Una encuesta pertenece a una empresa
            $table->unsignedBigInteger('idEmpresa');
            $table->foreign('idEmpresa')->references('idEmpresa')->on('empresas')->onDelete('cascade');
            
            //Una encuesta es creada por un usuario
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['idEmpresa']);
            $table->index(['user_id']);
            $table->index(['fechaInicio']);
            $table->index(['fechaFin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
