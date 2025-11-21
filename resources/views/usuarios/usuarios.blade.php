@extends('components.plantillaBase')

@push('styles')
@endpush
<title>Usuarios</title>

@section('content')
    <h2 style="margin-top: 30px;">Gestión de Usuarios Autorizados</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Administra los usuarios que pueden crear y analizar encuestas</p>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Usuarios Autorizados</h3>
            <a href="{{ url('/agregar-usuario') }}" class="btn btn-success">+ Agregar Usuario</a>   
        </div>

        <table id="usuariosTable" class="table is-striped">
            
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/agregarUsuario/listarUsuarios.js') }}"></script>
@endpush
