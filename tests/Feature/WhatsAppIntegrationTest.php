<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Encuestas;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class WhatsAppIntegrationTest extends TestCase
{
    public function test_envio_a_varios_numeros()
    {
        $_ENV['WHATSAPP_TOKEN'] = 'test-token';
        $_ENV['WHATSAPP_PHONE_NUMBER_ID'] = '1234567890';

        Schema::create('encuestas', function (Blueprint $table) {
            $table->bigIncrements('idEncuesta');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->string('estado');
            $table->string('enlaceLargo');
            $table->string('enlaceCorto');
            $table->string('codigoQR');
            $table->unsignedBigInteger('idEmpresa');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        $encuesta = Encuestas::create([
            'nombre' => 'Prueba',
            'descripcion' => null,
            'fechaInicio' => now()->toDateString(),
            'fechaFin' => now()->addDay()->toDateString(),
            'estado' => 'ACTIVA',
            'enlaceLargo' => config('app.url') . '/encuesta/temp',
            'enlaceCorto' => config('app.url') . '/s/temp',
            'codigoQR' => config('app.url') . '/qr/temp',
            'idEmpresa' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Http::fake([
            'https://graph.facebook.com/*' => Http::response(['success' => true], 200),
        ]);

        $response = $this->post(route('encuestas.whatsapp.enviar', ['id' => $encuesta->idEncuesta]), [
            'numeros' => '573001111111, 573002222222',
            'mensaje' => 'Hola',
        ]);

        $response->assertRedirect(route('encuestas.whatsapp', ['id' => $encuesta->idEncuesta]));
        $response->assertSessionHas('status');
    }
}