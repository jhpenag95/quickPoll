<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Encuestas;
use App\Models\Preguntas;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    public function index()
    {
        return view('reportes.reportes');
    }

    public function encuestasEmpresa(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([]);
        }
        $items = Encuestas::select('idEncuesta', 'nombre')
            ->where('idEmpresa', Auth::user()->empresa_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($items);
    }

    public function generar(Request $request)
    {
        $encuestaId = (int) $request->query('encuesta');
        if (!$encuestaId) {
            return response()->json(['error' => 'encuesta requerida'], 400);
        }
        $periodo = $request->query('periodo', 'todo');
        $range = $this->resolveRange($periodo, $request);
        $data = $this->buildReport($encuestaId, $range['desde'], $range['hasta']);
        return response()->json($data);
    }

    public function excel(Request $request)
    {
        $encuestaId = (int) $request->query('encuesta');
        if (!$encuestaId) {
            return response()->json(['error' => 'encuesta requerida'], 400);
        }
        $periodo = $request->query('periodo', 'todo');
        $range = $this->resolveRange($periodo, $request);
        $data = $this->buildReport($encuestaId, $range['desde'], $range['hasta']);

        $csv = [];
        $csv[] = 'Resumen General';
        $csv[] = $this->csvRow(['Encuesta', ($data['encuesta']['nombre'] ?? '')]);
        $csv[] = $this->csvRow(['Periodo', ($data['filtros']['periodo'] ?? '')]);
        $csv[] = $this->csvRow(['Desde', ($data['filtros']['desde'] ?? '')]);
        $csv[] = $this->csvRow(['Hasta', ($data['filtros']['hasta'] ?? '')]);
        $csv[] = $this->csvRow(['Total Respuestas', ($data['totalRespuestas'] ?? 0)]);
        $csv[] = $this->csvRow(['Promedio Satisfaccion', ($data['promedioSatisfaccion'] ?? '')]);
        $csv[] = '';
        foreach ($data['preguntas'] as $p) {
            $csv[] = 'Pregunta';
            $csv[] = $this->csvRow(['Texto', $p['texto']]);
            $csv[] = $this->csvRow(['Tipo', $p['tipo']]);
            $csv[] = $this->csvRow(['Total Pregunta', ($p['resumen']['total'] ?? '')]);
            if ($p['tipo'] === 'opcion-multiple') {
                $csv[] = $this->csvRow(['Opción', 'Cantidad', 'Porcentaje']);
                foreach ($p['resumen']['opciones'] as $o) {
                    $csv[] = $this->csvRow([$o['texto'], $o['cantidad'], $o['porcentaje']]);
                }
            } elseif ($p['tipo'] === 'si-no') {
                $csv[] = $this->csvRow(['Respuesta', 'Cantidad', 'Porcentaje']);
                $csv[] = $this->csvRow(['Si', $p['resumen']['si'], $p['resumen']['porcentajeSi']]);
                $csv[] = $this->csvRow(['No', $p['resumen']['no'], $p['resumen']['porcentajeNo']]);
            } elseif ($p['tipo'] === 'escala') {
                $csv[] = $this->csvRow(['Valor', 'Cantidad', 'Porcentaje']);
                foreach ($p['resumen']['distribucion'] as $d) {
                    $csv[] = $this->csvRow([(string) $d['valor'], $d['cantidad'], $d['porcentaje']]);
                }
                $csv[] = $this->csvRow(['Promedio', $p['resumen']['promedio']]);
            } else {
                $csv[] = 'Respuestas';
                foreach ($p['resumen']['recientes'] as $t) {
                    $csv[] = $this->csvRow([$t]);
                }
            }
            $csv[] = '';
        }

        $output = implode("\r\n", $csv) . "\r\n";
        return response()->streamDownload(function () use ($output) {
            echo $output;
        }, 'reporte.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function pdf(Request $request)
    {
        $encuestaId = (int) $request->query('encuesta');
        if (!$encuestaId) {
            return response()->json(['error' => 'encuesta requerida'], 400);
        }
        $periodo = $request->query('periodo', 'todo');
        $range = $this->resolveRange($periodo, $request);
        $data = $this->buildReport($encuestaId, $range['desde'], $range['hasta']);
        $pdf = Pdf::loadView('reportes.pdf', ['data' => $data]);
        return $pdf->download('reporte.pdf');
    }

    private function resolveRange(string $periodo, Request $request): array
    {
        $desde = null;
        $hasta = null;
        if ($periodo === 'hoy') {
            $desde = now()->startOfDay();
            $hasta = now();
        } elseif ($periodo === 'semana') {
            $desde = now()->subDays(7);
            $hasta = now();
        } elseif ($periodo === 'mes') {
            $desde = now()->subDays(30);
            $hasta = now();
        } elseif ($periodo === 'personalizado') {
            $desde = $request->query('desde');
            $hasta = $request->query('hasta');
        }
        return [
            'periodo' => $periodo,
            'desde' => $desde ? (string) $desde : ($request->query('desde') ?: ''),
            'hasta' => $hasta ? (string) $hasta : ($request->query('hasta') ?: ''),
        ];
    }

    private function buildReport(int $encuestaId, $desde, $hasta): array
    {
        $encuesta = Encuestas::find($encuestaId);
        $baseRespuestas = DB::table('respuesta_encuesta')
            ->where('idEncuesta', $encuestaId);
        if ($desde && $hasta) {
            $baseRespuestas = $baseRespuestas->whereBetween('fechaRespuesta', [$desde, $hasta]);
        }
        $totalRespuestas = (int) $baseRespuestas->count();

        $preguntas = Preguntas::where('idEncuesta', $encuestaId)
            ->orderBy('orden')
            ->get();

        $resultPreguntas = [];
        $escalaSum = 0;
        $escalaCount = 0;

        foreach ($preguntas as $pregunta) {
            $answers = DB::table('detalle_respuesta')
                ->join('respuesta_encuesta', 'detalle_respuesta.idRespuesta', '=', 'respuesta_encuesta.idRespuesta')
                ->where('respuesta_encuesta.idEncuesta', $encuestaId)
                ->where('detalle_respuesta.idPregunta', $pregunta->idPregunta);
            if ($desde && $hasta) {
                $answers = $answers->whereBetween('respuesta_encuesta.fechaRespuesta', [$desde, $hasta]);
            }

            if ($pregunta->tipoPregunta === 'opcion-multiple') {
                $optCol = null;
                if (Schema::hasColumn('detalle_respuesta', 'idOpcion')) {
                    $optCol = 'detalle_respuesta.idOpcion';
                } elseif (Schema::hasColumn('detalle_respuesta', 'idOpcionRespuesta')) {
                    $optCol = 'detalle_respuesta.idOpcionRespuesta';
                }
                $items = [];
                if ($optCol) {
                    $rows = (clone $answers)
                        ->select(DB::raw('COUNT(*) as cantidad'), DB::raw($optCol . ' as idOpcion'))
                        ->groupBy('idOpcion')
                        ->get();
                    $opciones = DB::table('opcionesrespuesta')
                        ->where('idPregunta', $pregunta->idPregunta)
                        ->orderBy('orden')
                        ->get()
                        ->keyBy('idOpcion');
                    $totalQ = (clone $answers)->count();
                    $mapRows = [];
                    foreach ($rows as $r) {
                        $mapRows[$r->idOpcion] = (int) $r->cantidad;
                    }
                    foreach ($opciones as $idOpt => $opt) {
                        $cant = $mapRows[$idOpt] ?? 0;
                        $porc = $totalQ > 0 ? round(($cant * 100) / $totalQ) : 0;
                        $items[] = [
                            'texto' => $opt->textoOpcion,
                            'cantidad' => $cant,
                            'porcentaje' => $porc,
                        ];
                    }
                } else {
                    $rows = (clone $answers)
                        ->select('textoRespuesta', DB::raw('COUNT(*) as cantidad'))
                        ->groupBy('textoRespuesta')
                        ->get();
                    $totalQ = (clone $answers)->count();
                    foreach ($rows as $r) {
                        $porc = $totalQ > 0 ? round(($r->cantidad * 100) / $totalQ) : 0;
                        $items[] = [
                            'texto' => $r->textoRespuesta,
                            'cantidad' => (int) $r->cantidad,
                            'porcentaje' => $porc,
                        ];
                    }
                }
                $resultPreguntas[] = [
                    'id' => $pregunta->idPregunta,
                    'texto' => $pregunta->textoPregunta,
                    'tipo' => 'opcion-multiple',
                    'resumen' => [
                        'opciones' => $items,
                        'total' => (clone $answers)->count(),
                    ],
                ];
            } elseif ($pregunta->tipoPregunta === 'si-no') {
                $rows = (clone $answers)
                    ->select('valorNumerico', DB::raw('COUNT(*) as cantidad'))
                    ->groupBy('valorNumerico')
                    ->get();
                $totalQ = (clone $answers)->count();
                $si = 0;
                $no = 0;
                foreach ($rows as $r) {
                    if ((int) $r->valorNumerico === 1) {
                        $si = (int) $r->cantidad;
                    } else {
                        $no = (int) $r->cantidad;
                    }
                }
                $porSi = $totalQ > 0 ? round(($si * 100) / $totalQ) : 0;
                $porNo = $totalQ > 0 ? round(($no * 100) / $totalQ) : 0;
                $resultPreguntas[] = [
                    'id' => $pregunta->idPregunta,
                    'texto' => $pregunta->textoPregunta,
                    'tipo' => 'si-no',
                    'resumen' => [
                        'si' => $si,
                        'no' => $no,
                        'porcentajeSi' => $porSi,
                        'porcentajeNo' => $porNo,
                        'total' => $totalQ,
                    ],
                ];
            } elseif ($pregunta->tipoPregunta === 'escala') {
                $totalQ = (clone $answers)->count();
                $rows = (clone $answers)
                    ->select('valorNumerico', DB::raw('COUNT(*) as cantidad'))
                    ->groupBy('valorNumerico')
                    ->get();
                $dist = [];
                foreach (range(1, 5) as $v) {
                    $match = $rows->firstWhere('valorNumerico', $v);
                    $cant = $match ? (int) $match->cantidad : 0;
                    $porc = $totalQ > 0 ? round(($cant * 100) / $totalQ) : 0;
                    $dist[] = [
                        'valor' => $v,
                        'cantidad' => $cant,
                        'porcentaje' => $porc,
                    ];
                }
                $avgRow = (clone $answers)->select(DB::raw('AVG(valorNumerico) as avg'))->first();
                $prom = $avgRow && $avgRow->avg ? round($avgRow->avg, 2) : 0;
                $escalaSum += $prom * $totalQ;
                $escalaCount += $totalQ;
                $resultPreguntas[] = [
                    'id' => $pregunta->idPregunta,
                    'texto' => $pregunta->textoPregunta,
                    'tipo' => 'escala',
                    'resumen' => [
                        'promedio' => $prom,
                        'distribucion' => $dist,
                        'total' => $totalQ,
                    ],
                ];
            } else {
                $totalQ = (clone $answers)->count();
                $recientes = (clone $answers)
                    ->select('textoRespuesta')
                    ->orderBy('detalle_respuesta.created_at', 'desc')
                    ->limit(100)
                    ->get()
                    ->pluck('textoRespuesta')
                    ->filter()
                    ->values()
                    ->all();
                $resultPreguntas[] = [
                    'id' => $pregunta->idPregunta,
                    'texto' => $pregunta->textoPregunta,
                    'tipo' => $pregunta->tipoPregunta,
                    'resumen' => [
                        'total' => $totalQ,
                        'recientes' => $recientes,
                    ],
                ];
            }
        }

        $promedioSatisfaccion = $escalaCount > 0 ? round($escalaSum / $escalaCount, 2) : null;

        return [
            'encuesta' => [
                'id' => $encuesta ? $encuesta->idEncuesta : $encuestaId,
                'nombre' => $encuesta ? $encuesta->nombre : '',
            ],
            'filtros' => [
                'periodo' => $desde && $hasta ? 'personalizado' : 'todo',
                'desde' => $desde ? (string) $desde : '',
                'hasta' => $hasta ? (string) $hasta : '',
            ],
            'totalRespuestas' => $totalRespuestas,
            'promedioSatisfaccion' => $promedioSatisfaccion,
            'preguntas' => $resultPreguntas,
        ];
    }

    private function csvRow(array $cols): string
    {
        $escaped = array_map(function ($c) {
            $s = (string) $c;
            $s = str_replace('"', '""', $s);
            if (str_contains($s, ',') || str_contains($s, '"') || str_contains($s, "\n")) {
                return '"' . $s . '"';
            }
            return $s;
        }, $cols);
        return implode(',', $escaped);
    }
}
