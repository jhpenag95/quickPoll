@extends('components.plantillaBase')

<title>Editar Encuesta</title>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/encuestas/CrearEncuesta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('content')
    <h2 style="margin-top: 30px;">Editar Encuesta</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Actualice la información de la encuesta</p>

    <div class="card">
        <form action="{{ route('encuestas.update', ['id' => $encuesta->idEncuesta]) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Información General -->
            <h3 style="margin-bottom: 15px; color: #2c3e50;">Información General</h3>

            <div class="form-group">
                <label for="nombre-encuesta">Nombre de la Encuesta *</label>
                <input type="text" id="nombre-encuesta" name="nombre" value="{{ $encuesta->nombre }}" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion">{{ $encuesta->descripcion }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="fecha-inicio">Fecha de Inicio *</label>
                    <input type="date" id="fecha-inicio" name="fechaInicio" value="{{ \Carbon\Carbon::parse($encuesta->fechaInicio)->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="fecha-fin">Fecha de Fin *</label>
                    <input type="date" id="fecha-fin" name="fechaFin" value="{{ \Carbon\Carbon::parse($encuesta->fechaFin)->format('Y-m-d') }}" required>
                </div>
            </div>

            <!-- Preguntas -->
            <h3 style="margin-top: 30px; margin-bottom: 15px; color: #2c3e50;">Preguntas</h3>

            <div id="preguntas-container">
                @php $idx = 1; @endphp
                @foreach ($preguntas as $pregunta)
                    <div class="pregunta-item">
                        <div class="form-group" id="contenedor-numero">
                            <label>Pregunta {{ $idx }} *</label>
                            <input type="text" name="pregunta-{{ $idx }}" value="{{ $pregunta->textoPregunta }}" placeholder="Escribe tu pregunta aquí" required>
                        </div>

                        <div class="form-group" id="contenedor-tipo-respuesta">
                            <label>Tipo de Respuesta</label>
                            <select name="tipo-respuesta-{{ $idx }}" class="tipo-respuesta">
                                <option value="opcion-multiple" {{ $pregunta->tipoPregunta === 'opcion-multiple' ? 'selected' : '' }}>Opción Múltiple</option>
                                <option value="texto-corto" {{ $pregunta->tipoPregunta === 'texto-corto' ? 'selected' : '' }}>Texto Corto</option>
                                <option value="texto-largo" {{ $pregunta->tipoPregunta === 'texto-largo' ? 'selected' : '' }}>Texto Largo</option>
                                <option value="escala" {{ $pregunta->tipoPregunta === 'escala' ? 'selected' : '' }}>Escala (1-5)</option>
                                <option value="si-no" {{ $pregunta->tipoPregunta === 'si-no' ? 'selected' : '' }}>Sí/No</option>
                            </select>
                        </div>

                        <div class="form-group" id="contenedor-opciones">
                            @if ($pregunta->tipoPregunta === 'opcion-multiple')
                                <label>Opciones de Respuesta (para opción múltiple)</label>
                                <div class="opciones-list">
                                    @php $opt = 1; @endphp
                                    @foreach (($opciones[$pregunta->idPregunta] ?? collect()) as $op)
                                        <div class="respuesta-item">
                                            <input type="text" name="opcion-{{ $idx }}-{{ $opt }}" value="{{ $op->textoOpcion }}" placeholder="Opción {{ $opt }}">
                                            <button type="button" class="btn btn-danger eliminar-opcion" style="padding: 5px 10px;">X</button>
                                        </div>
                                        @php $opt++; @endphp
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary agregar-opcion" style="margin-left: 20px; margin-top: 10px;">+ Agregar Opción</button>
                            @elseif ($pregunta->tipoPregunta === 'texto-corto')
                                <label>Vista previa</label>
                                <input type="text" placeholder="Respuesta corta">
                            @elseif ($pregunta->tipoPregunta === 'texto-largo')
                                <label>Vista previa</label>
                                <textarea placeholder="Respuesta larga"></textarea>
                            @elseif ($pregunta->tipoPregunta === 'escala')
                                <label>Vista previa</label>
                                <div class="escala">
                                    <label style="margin-right:8px;">1</label><input type="radio">
                                    <label style="margin:0 8px;">2</label><input type="radio">
                                    <label style="margin:0 8px;">3</label><input type="radio">
                                    <label style="margin:0 8px;">4</label><input type="radio">
                                    <label style="margin-left:8px;">5</label><input type="radio">
                                </div>
                            @elseif ($pregunta->tipoPregunta === 'si-no')
                                <label>Vista previa</label>
                                <select><option>Sí</option><option>No</option></select>
                            @endif
                        </div>

                        <button type="button" class="btn btn-danger eliminar-pregunta" style="margin-top: 10px;">Eliminar Pregunta</button>
                    </div>
                    @php $idx++; @endphp
                @endforeach
            </div>

            <button type="button" class="btn btn-success" style="margin-top: 15px;" onclick="agregarPregunta()">+ Agregar Pregunta</button>

            
            <!-- Botones de Acción -->
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="{{ url('/encuestas') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/encuestas/crearEncuesta.js') }}"></script>
@endpush
