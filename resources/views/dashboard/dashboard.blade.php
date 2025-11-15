@extends('components.plantillaBase')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
@endpush
<title>Dashboard</title>
@section('content')
    <h2 style="margin-top: 30px; margin-bottom: 20px;">Bienvenido, Usuario</h2>
    <p style="color: #7f8c8d; margin-bottom: 30px;">Empresa: Mi Empresa S.A.</p>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Encuestas Activas</div>
            <div class="stat-number">12</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Respuestas Recibidas</div>
            <div class="stat-number">345</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Usuarios Autorizados</div>
            <div class="stat-number">8</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Encuestas Totales</div>
            <div class="stat-number">25</div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="card">
        <h2>Acciones Rápidas</h2>
        <div class="quick-actions">
            <a href="crear-encuesta.html" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📝</div>
                <h3>Nueva Encuesta</h3>
                <p>Crear una nueva encuesta</p>
            </a>

            <a href="encuestas.html" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📋</div>
                <h3>Ver Encuestas</h3>
                <p>Gestionar encuestas</p>
            </a>

            <a href="reportes.html" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📊</div>
                <h3>Reportes</h3>
                <p>Ver resultados</p>
            </a>

            <a href="usuarios.html" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">👥</div>
                <h3>Usuarios</h3>
                <p>Gestionar usuarios</p>
            </a>
        </div>
    </div>

    <!-- Encuestas Recientes -->
    <div class="card">
        <h2>Encuestas Recientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha Creación</th>
                    <th>Respuestas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Satisfacción del Cliente - Soporte</td>
                    <td>2024-11-10</td>
                    <td>45</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="ver-encuesta.html" class="btn">Ver</a>
                            <a href="reportes.html" class="btn btn-success">Reporte</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Asistencia a Capacitaciones</td>
                    <td>2024-11-08</td>
                    <td>23</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="ver-encuesta.html" class="btn">Ver</a>
                            <a href="reportes.html" class="btn btn-success">Reporte</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Percepción de Aseo</td>
                    <td>2024-11-05</td>
                    <td>67</td>
                    <td><span class="badge badge-inactive">Finalizada</span></td>
                    <td>
                        <div class="actions">
                            <a href="ver-encuesta.html" class="btn">Ver</a>
                            <a href="reportes.html" class="btn btn-success">Reporte</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
