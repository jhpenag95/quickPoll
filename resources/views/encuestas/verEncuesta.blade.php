@extends('components.plantillaBase')

<title>Ver Encuesta</title>
@section('styles')
@endsection

@section('content')
    <h2 style="margin-top: 30px;">Detalles de la Encuesta</h2>

    <div class="card">
        <h3>Satisfacción del Cliente - Soporte Telefónico</h3>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Información general de la encuesta</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <strong>Estado:</strong> <span class="badge badge-active">Activa</span>
            </div>
            <div>
                <strong>Respuestas:</strong> 45
            </div>
            <div>
                <strong>Fecha Inicio:</strong> 10/11/2024
            </div>
            <div>
                <strong>Fecha Fin:</strong> 10/12/2024
            </div>
        </div>

        <h4 style="margin-top: 30px;">Enlaces de Distribución</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
            <p><strong>Enlace Largo:</strong></p>
            <input type="text" value="https://encuestas.miempresa.com/encuesta/satisfaccion-cliente-soporte-12345" readonly
                style="width: 100%; margin-top: 5px;">
            <button class="btn btn-secondary" style="margin-top: 10px;">Copiar</button>
        </div>

        <div style="margin-top: 20px;">
            <a href="crear-encuesta.html" class="btn">Editar</a>
            <a href="reportes.html" class="btn btn-success">Ver Reporte</a>
            <a href="encuestas.html" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
