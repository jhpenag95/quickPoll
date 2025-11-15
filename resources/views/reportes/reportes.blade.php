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
            <form>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <select id="empresa" name="empresa">
                            <option value="">Mi Empresa S.A.</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="encuesta">Encuesta</label>
                        <select id="encuesta" name="encuesta">
                            <option value="">Seleccione una encuesta</option>
                            <option value="1">Satisfacción del Cliente - Soporte</option>
                            <option value="2">Satisfacción con Envío de Productos</option>
                            <option value="3">Asistencia a Capacitaciones</option>
                            <option value="4">Percepción de Aseo</option>
                            <option value="5">Necesidades de Capacitación</option>
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
                    <button type="submit" class="btn">Generar Reporte</button>
                    <button type="button" class="btn btn-success">Descargar Excel (.xls)</button>
                    <button type="button" class="btn btn-danger">Descargar PDF</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="card">
        <h3>Resultados: Satisfacción del Cliente - Soporte Telefónico</h3>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Período: 10/11/2024 - 11/11/2024 | Total de respuestas: 45</p>

        <!-- Estadísticas Generales -->
        <div class="estadistica">
            <div class="stat-box">
                <h4>Total Respuestas</h4>
                <div class="numero">45</div>
            </div>
            <div class="stat-box">
                <h4>Promedio Satisfacción</h4>
                <div class="numero">4.2/5</div>
            </div>
            <div class="stat-box">
                <h4>Tasa de Respuesta</h4>
                <div class="numero">75%</div>
            </div>
            <div class="stat-box">
                <h4>Tiempo Promedio</h4>
                <div class="numero">2.5 min</div>
            </div>
        </div>

        <!-- Pregunta 1 -->
        <div class="resultado-item">
            <h4>1. ¿Cómo califica la atención recibida por nuestro equipo de soporte?</h4>
            <p style="color: #7f8c8d; margin-top: 5px;">Tipo: Escala 1-5 | Respuestas: 45</p>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Excelente (5)</span>
                    <span>20 respuestas (44%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 44%;">44%</div>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Muy Bueno (4)</span>
                    <span>15 respuestas (33%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 33%;">33%</div>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Bueno (3)</span>
                    <span>7 respuestas (16%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 16%;">16%</div>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Regular (2)</span>
                    <span>2 respuestas (4%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 4%;">4%</div>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Malo (1)</span>
                    <span>1 respuesta (2%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 2%;">2%</div>
                </div>
            </div>
        </div>

        <!-- Pregunta 2 -->
        <div class="resultado-item">
            <h4>2. ¿El problema fue resuelto satisfactoriamente?</h4>
            <p style="color: #7f8c8d; margin-top: 5px;">Tipo: Sí/No | Respuestas: 45</p>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Sí</span>
                    <span>38 respuestas (84%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 84%; background: #27ae60;">84%</div>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>No</span>
                    <span>7 respuestas (16%)</span>
                </div>
                <div class="barra-progreso">
                    <div class="barra-fill" style="width: 16%; background: #e74c3c;">16%</div>
                </div>
            </div>
        </div>

        <!-- Pregunta 3 -->
        <div class="resultado-item">
            <h4>3. ¿Qué podríamos mejorar en nuestro servicio?</h4>
            <p style="color: #7f8c8d; margin-top: 5px;">Tipo: Texto Largo | Respuestas: 32</p>

            <div style="margin-top: 15px; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                <p><strong>Respuestas recientes:</strong></p>
                <ul style="margin-top: 10px; margin-left: 20px;">
                    <li>"El tiempo de espera podría ser menor"</li>
                    <li>"Excelente servicio, muy satisfecho"</li>
                    <li>"Necesitan más personal en horas pico"</li>
                    <li>"Todo perfecto, gracias"</li>
                    <li>"Mejorar la comunicación durante el proceso"</li>
                </ul>
                <button class="btn btn-secondary" style="margin-top: 10px;">Ver todas las respuestas</button>
            </div>
        </div>

        <!-- Botones de Descarga -->
        <div style="margin-top: 30px; text-align: center;">
            <button class="btn btn-success">Descargar Reporte Completo (Excel)</button>
            <button class="btn btn-danger">Descargar Reporte Completo (PDF)</button>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
