<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Encuestas;
use App\Models\Preguntas;
use Illuminate\Support\Facades\DB;

class encuestasController extends Controller
{
    public function index()
    {
        return view('encuestas.encuestas');
    }

    public function crearEncuesta()
    {
        return view('encuestas.crearEncuesta');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'estado' => 'nullable|in:ACTIVA',
        ]);


        // ====== 
        // ===== Inicio para guardar la encuesta en la base de datos ====//

        $encuesta = new Encuestas();
        $encuesta->nombre       = $validated['nombre'];
        $encuesta->descripcion  = $validated['descripcion'] ?? null;
        $encuesta->fechaInicio  = $validated['fechaInicio'];
        $encuesta->fechaFin     = $validated['fechaFin'];
        $encuesta->estado       = $validated['estado'] ?? 'ACTIVA';
        $encuesta->idEmpresa    = Auth::user()->empresa_id;
        $encuesta->user_id      = Auth::id();

        // ⚠️ GENERAR LOS ENLACES ANTES DEL PRIMER SAVE
        $tokenTemp = Str::random(10);
        $encuesta->enlaceLargo  = rtrim(config('app.url'), '/') . '/encuesta/' . $tokenTemp;
        $encuesta->enlaceCorto  = rtrim(config('app.url'), '/') . '/s/' . Str::random(6);
        $encuesta->codigoQR     = rtrim(config('app.url'), '/') . '/qr/' . $tokenTemp;

        $encuesta->created_at   = now();
        $encuesta->updated_at   = now();
        $encuesta->save();

        // Actualizar con el ID real de la encuesta
        $encuesta->enlaceLargo  = rtrim(config('app.url'), '/') . '/encuesta/' . $encuesta->idEncuesta;
        $encuesta->codigoQR     = rtrim(config('app.url'), '/') . '/qr/' . $encuesta->idEncuesta;
        $encuesta->save();

        // ====== 
        // ===== Fin para guardar la encuesta en la base de datos ====//



        // ======
        // ===== Inicio para guradar datos en la tabla preguntas ====//

        $raw = $request->all();
        $preguntasData = [];
        foreach ($raw as $key => $value) {
            if (preg_match('/^pregunta-(\d+)$/', $key, $m)) {
                $idx = (int) $m[1];
                $preguntasData[$idx] = $preguntasData[$idx] ?? ['opciones' => []];
                $preguntasData[$idx]['pregunta'] = $value;
            } elseif (preg_match('/^tipo-respuesta-(\d+)$/', $key, $m)) {
                $idx = (int) $m[1];
                $preguntasData[$idx] = $preguntasData[$idx] ?? ['opciones' => []];
                $preguntasData[$idx]['tipo'] = $value;
            } elseif (preg_match('/^opcion-(\d+)-(\d+)$/', $key, $m)) {
                $pIdx = (int) $m[1];
                $oIdx = (int) $m[2];
                $preguntasData[$pIdx] = $preguntasData[$pIdx] ?? ['opciones' => []];
                $preguntasData[$pIdx]['opciones'][$oIdx] = $value;
            }
        }

        ksort($preguntasData);
        foreach ($preguntasData as $i => $p) {
            $preguntaModel = new Preguntas();
            $preguntaModel->idEncuesta    = $encuesta->idEncuesta;
            $preguntaModel->textoPregunta = $p['pregunta'] ?? '';
            $preguntaModel->tipoPregunta  = $p['tipo'] ?? 'texto-corto';
            $preguntaModel->obligatoria   = 0;
            $preguntaModel->orden         = $i;
            $preguntaModel->created_at    = now();
            $preguntaModel->updated_at    = now();
            $preguntaModel->save();

            if (($p['tipo'] ?? '') === 'opcion-multiple' && !empty($p['opciones'])) {
                ksort($p['opciones']);
                $orden = 1;
                foreach ($p['opciones'] as $texto) {
                    DB::table('opcionesrespuesta')->insert([
                        'textoOpcion' => $texto,
                        'valor'       => null,
                        'orden'       => $orden++,
                        'idPregunta'  => $preguntaModel->idPregunta,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        }

        return redirect()->route('encuestas')
            ->with('status', 'Encuesta creada correctamente')
            ->with('enlaceLargo', $encuesta->enlaceLargo)
            ->with('enlaceCorto', $encuesta->enlaceCorto)
            ->with('codigoQR', $encuesta->codigoQR);
    }

    public function public($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        return response('Encuesta: ' . $encuesta->nombre);
    }

    public function short($code)
    {
        $full = rtrim(config('app.url'), '/') . '/s/' . $code;
        $encuesta = Encuestas::where('enlaceCorto', $full)->first();
        if (!$encuesta) {
            abort(404);
        }
        return redirect()->to($encuesta->enlaceLargo);
    }
}
