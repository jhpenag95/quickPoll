@extends('components.plantillaBase')

<title>Ver Encuesta</title>
@section('styles')
@endsection

@section('content')
    <h2 style="margin-top: 30px;">Detalles de la Encuesta</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">ID de la Encuesta: {{ $encuesta->idEncuesta }}</p>
    
    <div class="card">
        <h3>{{ $encuesta->nombre }}</h3>
        <p style="color: #7f8c8d; margin-bottom: 20px;">{{ $encuesta->descripcion }}</p>    

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <strong>Estado:</strong>
                @php $isActive = strtoupper($encuesta->estado) === 'ACTIVA'; @endphp
                <span class="badge {{ $isActive ? 'badge-active' : 'badge-inactive' }}">{{ $isActive ? 'Activa' : 'Inactiva' }}</span>
            </div>
            <div>
                <strong>Respuestas:</strong> {{ $respuestasCount }}
            </div>
            <div>
                <strong>Fecha Inicio:</strong> {{ $encuesta->fechaInicio }}
            </div>
            <div>
                <strong>Fecha Fin:</strong> {{ $encuesta->fechaFin }}
            </div>
        </div>

        <h4 style="margin-top: 30px;">Enlaces de Distribución</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
            <p><strong>Enlace Largo:</strong></p>
            <input type="text" value="{{ $enlacePublico }}" readonly
                style="width: 100%; margin-top: 5px;">
            <button class="btn btn-secondary" style="margin-top: 10px;">Copiar</button>
        </div>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
            <p><strong>Enlace corto:</strong></p>
            <input type="text" value="{{ $encuesta->enlaceCorto }}" readonly   
                style="width: 100%; margin-top: 5px;">
            <button class="btn btn-secondary" style="margin-top: 10px;">Copiar</button>
        </div>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
            <p><strong>QR:</strong></p>
            <img src="{{ url('/qr/'.$encuesta->idEncuesta) }}" alt="QR" style="width: 140px; margin-top: 5px;">
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ url('/encuestas/editarEncuesta/'.$encuesta->idEncuesta) }}" class="btn">Editar</a>
            <a href="{{ url('/reportes') }}" class="btn btn-success">Ver Reporte</a>
            <a href="{{ url('/encuestas') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
