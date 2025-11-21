<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #2c3e50; }
        h1 { font-size: 20px; }
        h2 { font-size: 16px; margin-top: 20px; }
        h3 { font-size: 14px; margin-top: 15px; }
        .stat { display: flex; gap: 20px; margin: 10px 0; }
        .box { border: 1px solid #ddd; padding: 8px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f4f6f7; }
    </style>
    </head>
<body>
    <h1>Reporte de Encuesta</h1>
    <div>
        <div>Encuesta: {{ $data['encuesta']['nombre'] }}</div>
        <div>Periodo: {{ $data['filtros']['periodo'] }}</div>
        <div>Desde: {{ $data['filtros']['desde'] }}</div>
        <div>Hasta: {{ $data['filtros']['hasta'] }}</div>
    </div>

    <div class="stat">
        <div class="box">Total Respuestas: {{ $data['totalRespuestas'] }}</div>
        <div class="box">Promedio Satisfacción: {{ $data['promedioSatisfaccion'] ?? 'N/D' }}</div>
    </div>

    @foreach ($data['preguntas'] as $p)
        <h2>{{ $p['texto'] }}</h2>
        <div>Tipo: {{ $p['tipo'] }}</div>
        <div>Total Pregunta: {{ $p['resumen']['total'] ?? '' }}</div>
        @if ($p['tipo'] === 'opcion-multiple')
            <table>
                <thead>
                    <tr>
                        <th>Opción</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($p['resumen']['opciones'] as $o)
                        <tr>
                            <td>{{ $o['texto'] }}</td>
                            <td>{{ $o['cantidad'] }}</td>
                            <td>{{ $o['porcentaje'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($p['tipo'] === 'si-no')
            <table>
                <thead>
                    <tr>
                        <th>Respuesta</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sí</td>
                        <td>{{ $p['resumen']['si'] }}</td>
                        <td>{{ $p['resumen']['porcentajeSi'] }}</td>
                    </tr>
                    <tr>
                        <td>No</td>
                        <td>{{ $p['resumen']['no'] }}</td>
                        <td>{{ $p['resumen']['porcentajeNo'] }}</td>
                    </tr>
                </tbody>
            </table>
        @elseif ($p['tipo'] === 'escala')
            <div>Promedio: {{ $p['resumen']['promedio'] }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Valor</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($p['resumen']['distribucion'] as $d)
                        <tr>
                            <td>{{ $d['valor'] }}</td>
                            <td>{{ $d['cantidad'] }}</td>
                            <td>{{ $d['porcentaje'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Respuestas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Texto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($p['resumen']['recientes'] as $t)
                        <tr>
                            <td>{{ $t }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>
</html>