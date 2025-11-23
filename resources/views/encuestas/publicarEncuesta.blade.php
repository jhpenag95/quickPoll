@extends('components.plantillaBase')

@section('title', $encuesta->nombre . ' - Encuesta')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/encuestas/publicarEncuesta.css') }}">
@endpush

@section('content')
    <div class="encuesta-container">
        <div class="encuesta-card">
            <div class="encuesta-header">
                <h1 class="encuesta-title">{{ $encuesta->nombre }}</h1>
                @if ($encuesta->descripcion)
                    <p class="encuesta-descripcion">{{ $encuesta->descripcion }}</p>
                @endif
            </div>

            <form id="form-encuesta" action="{{ route('encuestas.responder', ['id' => $encuesta->idEncuesta]) }}" method="POST">
                @csrf
                <input type="hidden" name="duracion" id="duracion" value="0">

                @foreach ($preguntas as $index => $pregunta)
                    <div class="pregunta-card">
                        <label class="pregunta-label">
                            <span class="pregunta-numero">{{ $index + 1 }}</span>
                            {{ $pregunta->textoPregunta }}
                        </label>

                        @php
                            $tipo = $pregunta->tipoPregunta;
                            $name = 'respuesta-' . $pregunta->idPregunta;
                            $ops = $opciones[$pregunta->idPregunta] ?? collect();
                        @endphp

                        @if ($tipo === 'opcion-multiple')
                            @foreach ($ops as $op)
                                <div class="opcion-item">
                                    <label class="opcion-label">
                                        <input type="radio" name="{{ $name }}" value="{{ $op->idOpcion }}"
                                            required>
                                        <span>{{ $op->textoOpcion }}</span>
                                    </label>
                                </div>
                            @endforeach
                        @elseif ($tipo === 'si-no')
                            <div class="opcion-item">
                                <label class="opcion-label">
                                    <input type="radio" name="{{ $name }}" value="si" required>
                                    <span>Sí</span>
                                </label>
                            </div>
                            <div class="opcion-item">
                                <label class="opcion-label">
                                    <input type="radio" name="{{ $name }}" value="no" required>
                                    <span>No</span>
                                </label>
                            </div>
                        @elseif ($tipo === 'escala')
                            <div class="escala-container">
                                <div class="escala-wrapper">
                                    <span class="escala-label">1</span>
                                    <input type="range" min="1" max="5" value="3"
                                        name="{{ $name }}" class="range-slider"
                                        oninput="this.nextElementSibling.nextElementSibling.textContent = this.value"
                                        required>
                                    <span class="escala-label">5</span>
                                </div>
                                <span class="range-value">3</span>
                            </div>
                        @elseif ($tipo === 'texto-largo')
                            <textarea name="{{ $name }}" class="textarea-field" placeholder="Escribe tu respuesta aquí..." required></textarea>
                        @else
                            <input type="text" name="{{ $name }}" class="input-text"
                                placeholder="Tu respuesta..." required>
                        @endif
                    </div>
                @endforeach
                    

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">
                        Enviar Respuestas
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
  (function(){
    var start = Date.now();
    var form = document.getElementById('form-encuesta');
    var hidden = document.getElementById('duracion');
    if (form && hidden) {
      form.addEventListener('submit', function(){
        var secs = Math.floor((Date.now() - start) / 1000);
        hidden.value = String(secs);
      });
    }
  })();
</script>
@endpush
