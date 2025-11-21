@extends('components.plantillaBase')

@push('styles')

@endpush
@section('title', 'Encuestas')

@section('content')
    <h2 style="margin-top: 30px;">Gestión de Encuestas</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Crea, modifica y administra tus encuestas</p>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Mis Encuestas</h3>
            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn btn-success">+ Nueva Encuesta</a>
        </div>

        <table id="encuestasTableBody" class="table is-striped">
      
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/encuestas/listarEncuestas.js') }}"></script>
@endpush
