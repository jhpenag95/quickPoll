@extends('components.plantillaBase')

@section('title', 'Enviar por WhatsApp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('content')
    <div class="card" style="margin-top: 30px;">
        <h3>Enviar encuesta por WhatsApp</h3>
        {{-- <p style="color:#7f8c8d;">{{ $encuesta->nombre }}</p> --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div style="background:#f8f9fa; padding:15px; border-radius:5px; margin-top:10px;">
            <p><strong>Enlace público:</strong></p>
            {{-- <input type="text" value="{{ $enlace }}" readonly style="width:100%;"> --}}
        </div>
{{-- <form action="{{ route('encuestas.whatsapp.enviar', ['id' => $encuesta->idEncuesta]) }}" method="POST" style="margin-top:20px;"> --}}
        <form action="" method="POST" style="margin-top:20px;">
            @csrf
            <label><strong>Números inscritos</strong></label>
            {{-- <textarea name="numeros" rows="6" style="width:100%;" placeholder="Ej: 573001234567, 573009876543">{{ implode("\n", $numerosInscritos) }}</textarea> --}}
            <textarea name="numeros" rows="6" style="width:100%;" placeholder="Ej: 573001234567, 573009876543"></textarea>
            <label style="margin-top:15px;"><strong>Mensaje</strong></label>
            {{-- <textarea name="mensaje" rows="3" style="width:100%;">{{ 'Hola, te invitamos a responder la encuesta: ' . $enlace }}</textarea> --}}
            <textarea name="mensaje" rows="3" style="width:100%;">{{ 'Hola, te invitamos a responder la encuesta: '  }}</textarea>
            <div style="margin-top:20px;">
                <button class="btn btn-success" type="submit">Enviar por WhatsApp</button>
                <a class="btn btn-secondary" href="{{ route('encuestas') }}">Volver</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@endpush