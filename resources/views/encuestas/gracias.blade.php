@extends('components.plantillaBase')

@section('title', '¡Gracias por participar! - ' . $encuesta->nombre)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/encuestas/gracias.css') }}">
@endpush

@section('content')
    <div class="success-container">
        <div class="decorative-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

        <div class="success-card">
            <div class="success-icon">
                <svg viewBox="0 0 52 52">
                    <polyline points="14 27 22 35 38 19"/>
                </svg>
            </div>

            <h1 class="success-title">¡Respuesta Enviada!</h1>
            
            <p class="success-subtitle">Tu opinión ha sido registrada exitosamente</p>
            
            <div class="encuesta-name">
                "{{ $encuesta->nombre }}"
            </div>

            <div class="success-message">
                <strong>Gracias por tu tiempo y participación.</strong><br>
                Tus respuestas son muy valiosas y nos ayudarán a mejorar continuamente.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush