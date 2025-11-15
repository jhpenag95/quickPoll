@extends('components.plantillaBase')

<title>Crear Encuesta</title>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/encuestas/CrearEncuesta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('content')
    <h2 style="margin-top: 30px;">Crear Nueva Encuesta</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Complete la información de la encuesta</p>

    <div class="card">
        <form action="{{ route('encuestas.store') }}" method="POST">
            @csrf
            <!-- Información General -->
            <h3 style="margin-bottom: 15px; color: #2c3e50;">Información General</h3>

            <div class="form-group">
                <label for="nombre-encuesta">Nombre de la Encuesta *</label>
                <input type="text" id="nombre-encuesta" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="fecha-inicio">Fecha de Inicio *</label>
                    <input type="date" id="fecha-inicio" name="fechaInicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha-fin">Fecha de Fin *</label>
                    <input type="date" id="fecha-fin" name="fechaFin" required>
                </div>
            </div>

            <!-- Preguntas -->
            <h3 style="margin-top: 30px; margin-bottom: 15px; color: #2c3e50;">Preguntas</h3>

            <div id="preguntas-container">
                <div class="pregunta-item">
                    <div class="form-group" id="contenedor-numero">
                        <label>Pregunta 1 *</label>
                        <input type="text" name="pregunta-1" placeholder="Escribe tu pregunta aquí" required>
                    </div>

                    <div class="form-group" id="contenedor-tipo-respuesta">
                        <label>Tipo de Respuesta</label>
                        <select name="tipo-respuesta-1" class="tipo-respuesta">
                            <option value="opcion-multiple">Opción Múltiple</option>
                            <option value="texto-corto">Texto Corto</option>
                            <option value="texto-largo">Texto Largo</option>
                            <option value="escala">Escala (1-5)</option>
                            <option value="si-no">Sí/No</option>
                        </select>
                    </div>

                    <div class="form-group" id="contenedor-opciones"></div>

                    <button type="button" class="btn btn-danger eliminar-pregunta" style="margin-top: 10px;">Eliminar Pregunta</button>
                </div>
            </div>

            <button type="button" class="btn btn-success" style="margin-top: 15px;" onclick="agregarPregunta()">+ Agregar Pregunta</button>

            <input type="hidden" name="idEmpresa" value="{{ auth()->user()->empresa_id }}">
            <input type="hidden" name="estado" id="estado">
            <!-- Botones de Acción -->
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success" onclick="guardarEncuesta(event)">Guardar Encuesta</button>
                <a href="{{ route('encuestas') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/encuestas/enlacesDeDistribucion.js') }}"></script>
    <script src="{{ asset('js/encuestas/crearEncuesta.js') }}"></script>
@endpush
