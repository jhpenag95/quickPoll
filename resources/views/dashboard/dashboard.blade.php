@extends('components.plantillaBase')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
@endpush
<title>Dashboard</title>
@section('content')
    <h2 style="margin-top: 30px; margin-bottom: 20px;">Bienvenido, {{ Auth::user()->nombre }}</h2>
    <p style="color: #7f8c8d; margin-bottom: 30px;"><strong>Empresa:</strong> {{ Auth::user()->empresa->nombre }}</p>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Encuestas Activas</div>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Respuestas Recibidas</div>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Usuarios Autorizados</div>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Encuestas Totales</div>
            <div class="stat-number">0</div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="card">
        <h2>Acciones Rápidas</h2>
        <div class="quick-actions">
            <a href="{{ route('crearEncuesta') }}" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📝</div>
                <h3>Nueva Encuesta</h3>
                <p>Crear una nueva encuesta</p>
            </a>

            <a href="{{ route('encuestas') }}" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📋</div>
                <h3>Ver Encuestas</h3>
                <p>Gestionar encuestas</p>
            </a>

            <a href="{{ route('reportes') }}" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">📊</div>
                <h3>Reportes</h3>
                <p>Ver resultados</p>
            </a>

            <a href="{{ route('usuarios') }}" class="action-card" style="text-decoration: none; color: inherit;">
                <div class="action-icon">👥</div>
                <h3>Usuarios</h3>
                <p>Gestionar usuarios</p>
            </a>
        </div>
    </div>

    <!-- Encuestas Recientes -->
    <div class="card">
        <h2>Encuestas Recientes</h2>
        <table id="recentTable">
          
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
@endpush
