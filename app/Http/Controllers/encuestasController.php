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
use Illuminate\Support\Facades\Schema;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class encuestasController extends Controller
{
    public function index()
    {
        return view('encuestas.encuestas');
    }

    public function whatsapp_encuesta()
    {
        return view('encuestas.whatsapp');
    }

    public function crearEncuesta()
    {
        return view('encuestas.crearEncuesta');
    }

    public function listarEncuestas()
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        $encuestas = Encuestas::select(
            'encuestas.idEncuesta',
            'encuestas.nombre',
            'encuestas.created_at',
            DB::raw('COUNT(respuesta_encuesta.idRespuesta) as cantidad'),
            'encuestas.fechaInicio',
            'encuestas.fechaFin',
            'encuestas.estado'
        )
            ->where('encuestas.idEmpresa', Auth::user()->empresa_id)
            ->leftJoin('respuesta_encuesta', 'encuestas.idEncuesta', '=', 'respuesta_encuesta.idEncuesta')
            ->groupBy(
                'encuestas.idEncuesta',
                'encuestas.nombre',
                'encuestas.created_at',
                'encuestas.fechaInicio',
                'encuestas.fechaFin',
                'encuestas.estado'
            )
            ->get();

        return response()->json($encuestas);
    }

    public function editarEncuesta($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $preguntas = Preguntas::where('idEncuesta', $encuesta->idEncuesta)
            ->orderBy('orden')
            ->get();
        $opciones = DB::table('opcionesrespuesta')
            ->whereIn('idPregunta', $preguntas->pluck('idPregunta'))
            ->orderBy('orden')
            ->get()
            ->groupBy('idPregunta');

        return view('encuestas.editarEncuesta', [
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
            'opciones' => $opciones,
        ]);
    }

    public function verEncuesta($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $preguntas = Preguntas::where('idEncuesta', $encuesta->idEncuesta)
            ->orderBy('orden')
            ->get();
        $opciones = DB::table('opcionesrespuesta')
            ->whereIn('idPregunta', $preguntas->pluck('idPregunta'))
            ->orderBy('orden')
            ->get()
            ->groupBy('idPregunta');
        $respuestasCount = DB::table('respuesta_encuesta')
            ->where('idEncuesta', $encuesta->idEncuesta)
            ->count();
        $enlacePublico = route('encuestas.public', ['id' => $encuesta->idEncuesta]);
        return view('encuestas.verEncuesta', [
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
            'opciones' => $opciones,
            'respuestasCount' => $respuestasCount,
            'enlacePublico' => $enlacePublico,
        ]);
    }

    public function qr($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $url = route('encuestas.public', ['id' => $encuesta->idEncuesta]);
        $renderer = new ImageRenderer(
            new RendererStyle(256),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($url);
        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
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

    public function update(Request $request, $id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        $encuesta->nombre = $validated['nombre'];
        $encuesta->descripcion = $validated['descripcion'] ?? null;
        $encuesta->fechaInicio = $validated['fechaInicio'];
        $encuesta->fechaFin = $validated['fechaFin'];
        $encuesta->updated_at = now();
        $encuesta->save();

        DB::transaction(function () use ($encuesta, $request) {
            $pregs = Preguntas::where('idEncuesta', $encuesta->idEncuesta)->get();
            if ($pregs->count() > 0) {
                DB::table('opcionesrespuesta')
                    ->whereIn('idPregunta', $pregs->pluck('idPregunta'))
                    ->delete();
            }
            DB::table('preguntas')->where('idEncuesta', $encuesta->idEncuesta)->delete();

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
                $preguntaModel->idEncuesta = $encuesta->idEncuesta;
                $preguntaModel->textoPregunta = $p['pregunta'] ?? '';
                $preguntaModel->tipoPregunta = $p['tipo'] ?? 'texto-corto';
                $preguntaModel->obligatoria = 0;
                $preguntaModel->orden = $i;
                $preguntaModel->created_at = now();
                $preguntaModel->updated_at = now();
                $preguntaModel->save();

                if (($p['tipo'] ?? '') === 'opcion-multiple' && !empty($p['opciones'])) {
                    ksort($p['opciones']);
                    $orden = 1;
                    foreach ($p['opciones'] as $texto) {
                        DB::table('opcionesrespuesta')->insert([
                            'textoOpcion' => $texto,
                            'valor' => null,
                            'orden' => $orden++,
                            'idPregunta' => $preguntaModel->idPregunta,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('encuestas')
            ->with('status', 'Encuesta actualizada correctamente');
    }

    

    public function public($id)
    {
        // Buscar la encuesta por ID
        $encuesta = Encuestas::findOrFail($id);

        // Verificar si la encuesta ya ha sido respondida
        if (request()->session()->get('encuesta_'.$encuesta->idEncuesta.'_respondida')) {
            return redirect()->route('encuestas.gracias', ['id' => $encuesta->idEncuesta]);
        }

        // Obtener preguntas y opciones para la encuesta
        $preguntas = Preguntas::where('idEncuesta', $encuesta->idEncuesta)
            ->orderBy('orden')
            ->get();

        // Obtener opciones para las preguntas
        $opciones = DB::table('opcionesrespuesta')
            ->whereIn('idPregunta', $preguntas->pluck('idPregunta'))
            ->orderBy('orden')
            ->get()
            ->groupBy('idPregunta');

        // Renderizar la vista con los datos de la encuesta, preguntas y opciones
        return response()->view('encuestas.publicarEncuesta', [
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
            'opciones' => $opciones,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    // ======
    // ===== Inicio para redirigir a la encuesta pública por enlace corto ====//
    public function short($code)
    {
        // Obtener el enlace largo correspondiente al enlace corto para redirigir
        $full = rtrim(config('app.url'), '/') . '/s/' . $code;
        $encuesta = Encuestas::where('enlaceCorto', $full)->first();
        if (!$encuesta) {
            abort(404);
        }
        // Redirigir al enlace largo de la encuesta
        return redirect()->to($encuesta->enlaceLargo);
    }

    // ======
    // ===== Inicio para guradar datos en la tabla respuesta_encuesta ====//
    public function responder(Request $request, $id)
    {
        $encuesta = Encuestas::findOrFail($id);

        $respuestaId = DB::table('respuesta_encuesta')->insertGetId([
            'fechaRespuesta'        => now(),
            'canalRespuesta'        => 'web',
            'ipUsuario'             => $request->ip(),// Obtener la IP del usuario
            'completada'            => true,
            'idEncuesta'            => $encuesta->idEncuesta,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        // ======
        // ===== Inicio para guradar datos en la tabla detalle_respuesta ====//
        $preguntas = Preguntas::where('idEncuesta', $encuesta->idEncuesta)->get();
        foreach ($preguntas as $pregunta) {
            $campo = 'respuesta-' . $pregunta->idPregunta;
            if (!$request->has($campo)) {
                continue;
            }

            $tipo = $pregunta->tipoPregunta;
            $texto = null;
            $valorNumerico = null;
            $idOpcion = null;

            if ($tipo === 'opcion-multiple') {
                $idOpcion = (int) $request->input($campo);
                $opcion = DB::table('opcionesrespuesta')->where('idOpcion', $idOpcion)->first();
                $texto = $opcion ? $opcion->textoOpcion : null;
            } elseif ($tipo === 'escala') {
                $valorNumerico = (int) $request->input($campo);
            } elseif ($tipo === 'si-no') {
                $texto = $request->input($campo) === 'si' ? 'si' : 'no';
                $valorNumerico = $texto === 'si' ? 1 : 0;
            } elseif ($tipo === 'texto-corto' || $tipo === 'texto-largo') {
                $texto = $request->input($campo);
            }

            $valorNumerico = $valorNumerico ?? 0; // Asignar 0 si es null

            $payload = [
                'textoRespuesta'        => $texto,
                'valorNumerico'         => $valorNumerico,
                'idRespuesta'           => $respuestaId,
                'idPregunta'            => $pregunta->idPregunta,
                'created_at'            => now(),
                'updated_at'            => now(),
            ];

            //@Schema::description('Permite guardar los datos de la respuesta en la tabla detalle_respuesta')
            //payload: permite guardar los datos de la respuesta en la tabla detalle_respuesta
            //si es opcion multiple, guardar idOpcion o idOpcionRespuesta en el payload
            if (!is_null($idOpcion)) {
                if (Schema::hasColumn('detalle_respuesta', 'idOpcion')) {
                    $payload['idOpcion'] = $idOpcion;
                } elseif (Schema::hasColumn('detalle_respuesta', 'idOpcionRespuesta')) {
                    $payload['idOpcionRespuesta'] = $idOpcion;
                }
            }

            DB::table('detalle_respuesta')->insert($payload);
        }

        // Marcar la encuesta como respondida en la sesión
        $request->session()->put('encuesta_'.$encuesta->idEncuesta.'_respondida', true);
        return redirect()->route('encuestas.gracias', ['id' => $encuesta->idEncuesta]);
    }

    // ======
    // ===== Inicio para mostrar la página de agradecimiento ====//
    public function gracias($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        return response()->view('encuestas.gracias', [
            'encuesta' => $encuesta,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    public function whatsapp($id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $enlace = route('encuestas.public', ['id' => $encuesta->idEncuesta]);
        $numerosInscritos = [];
        $empresa = Auth::user() ? Auth::user()->empresa : null;
        if ($empresa && $empresa->telefono) {
            $numerosInscritos[] = $empresa->telefono;
        }
        return view('encuestas.whatsapp', [
            'encuesta' => $encuesta,
            'enlace' => $enlace,
            'numerosInscritos' => $numerosInscritos,
        ]);
    }

    public function enviarWhatsapp(Request $request, $id)
    {
        $encuesta = Encuestas::findOrFail($id);
        $data = $request->validate([
            'numeros' => 'required|string',
            'mensaje' => 'nullable|string',
        ]);
        $token = env('WHATSAPP_TOKEN');
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        if (!$token || !$phoneId) {
            return back()->withErrors(['whatsapp' => 'Configuración de WhatsApp no disponible']);
        }
        $numeros = collect(preg_split('/[\s,;\n\r]+/', $data['numeros']))
            ->map(fn ($n) => preg_replace('/\D+/', '', $n))
            ->filter()
            ->unique()
            ->values();
        $mensajeBase = $data['mensaje'] ?? 'Por favor responde la encuesta: ' . route('encuestas.public', ['id' => $encuesta->idEncuesta]);
        $resultados = [];
        foreach ($numeros as $to) {
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => true,
                    'body' => $mensajeBase,
                ],
            ];
            $resp = Http::withToken($token)->post('https://graph.facebook.com/v20.0/' . $phoneId . '/messages', $payload);
            $resultados[] = [
                'to' => $to,
                'status' => $resp->status(),
            ];
        }
        $enviados = collect($resultados)->where('status', 200)->count();
        $fallidos = $numeros->count() - $enviados;
        return redirect()->route('encuestas.whatsapp', ['id' => $encuesta->idEncuesta])
            ->with('status', 'Mensajes enviados: ' . $enviados . ' | Fallidos: ' . $fallidos);
    }
}
