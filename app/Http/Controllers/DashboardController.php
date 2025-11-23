<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Encuestas;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }

    public function statistics(Request $request)
    {
        $empresaId = Auth::check() ? Auth::user()->empresa_id : null;
        if (!$empresaId) {
            return response()->json([
                'counts' => [
                    'encuestas_activas' => 0,
                    'respuestas_recibidas' => 0,
                    'usuarios_autorizados' => 0,
                    'encuestas_totales' => 0,
                ],
                'recientes' => [],
            ]);
        }

        $encuestasActivas = DB::table('encuestas')
            ->where('idEmpresa', $empresaId)
            ->where('estado', 'ACTIVA')
            ->count();

        $encuestasTotales = DB::table('encuestas')
            ->where('idEmpresa', $empresaId)
            ->count();

        $respuestasRecibidas = DB::table('respuesta_encuesta')
            ->join('encuestas', 'respuesta_encuesta.idEncuesta', '=', 'encuestas.idEncuesta')
            ->where('encuestas.idEmpresa', $empresaId)
            ->count();

        $usuariosAutorizados = DB::table('users')
            ->where('empresa_id', $empresaId)
            ->count();

        $recientes = DB::table('encuestas')
            ->select(
                'encuestas.idEncuesta',
                'encuestas.nombre',
                'encuestas.created_at',
                'encuestas.estado',
                DB::raw('COUNT(respuesta_encuesta.idRespuesta) as respuestas')
            )
            ->leftJoin('respuesta_encuesta', 'encuestas.idEncuesta', '=', 'respuesta_encuesta.idEncuesta')
            ->where('encuestas.idEmpresa', $empresaId)
            ->groupBy('encuestas.idEncuesta', 'encuestas.nombre', 'encuestas.created_at', 'encuestas.estado')
            ->orderBy('encuestas.created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'counts' => [
                'encuestas_activas' => (int) $encuestasActivas,
                'respuestas_recibidas' => (int) $respuestasRecibidas,
                'usuarios_autorizados' => (int) $usuariosAutorizados,
                'encuestas_totales' => (int) $encuestasTotales,
            ],
            'recientes' => $recientes,
        ]);
    }
}
