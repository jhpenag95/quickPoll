@extends('components.plantillaBase')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reportes/reportes.css') }}">
@endpush
<title>Reportes</title>
@section('content')
    <h2 style="margin-top: 30px;">Reportes de Encuestas</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Consulta y descarga los resultados de tus encuestas</p>

    <!-- Filtros -->
    <div class="card">
        <h3>Filtros de Búsqueda</h3>
        <div class="filtros-section">
            <form id="reportesForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <select id="empresa" name="empresa">
                            <option value="{{ auth()->user()->empresa_id ?? '' }}">{{ optional(auth()->user()->empresa)->nombre ?? 'No tiene empresa' }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="encuesta">Encuesta</label>
                        <select id="encuesta" name="encuesta">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="periodo">Período</label>
                        <select id="periodo" name="periodo">
                            <option value="todo">Todo el tiempo</option>
                            <option value="hoy">Hoy</option>
                            <option value="semana">Última semana</option>
                            <option value="mes">Último mes</option>
                            <option value="personalizado">Personalizado</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <button type="button" id="btnGenerar" class="btn btn-primary">Generar Reporte</button>
                    <button type="button" id="btnExcel" class="btn btn-success">Descargar Excel (.xls)</button>
                    <button type="button" id="btnPdf" class="btn btn-danger">Descargar PDF</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="card">
        <h3 id="tituloResultados">Resultados</h3>
        <div id="resultadosContainer"></div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/reportes/resportes.js') }}"></script>
@endpush
