@extends('components.plantillaBase')

@section('styles')
@endsection

@section('content')
    <div class="card" style="max-width: 600px; margin: 40px auto;">
        <h2>Agregar Usuario Autorizado</h2>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Complete el formulario para agregar un nuevo usuario</p>

        <form action="usuarios.html" method="post">

            {{-- aqui se obtiene el id de la empresa del usuario autenticado --}}
            <input type="hidden" name="idEmpresa" id="idEmpresa" value="{{ auth()->user()->empresa_id }}">

            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol *</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="creador">Creador de Encuestas</option>
                    <option value="analista">Analista</option>
                </select>
            </div>

            <div class="form-group">
                <label for="estado">Estado *</label>
                <select id="estado" name="estado" required>
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Contraseña Temporal *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirmar Contraseña *</label>
                <input type="password" id="password-confirm" name="password-confirm" required>
            </div>
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success" onclick="validarFormulario(event)">Agregar Usuario</button>
                <a href="usuarios.html" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/agregarUsuario/agregarUsuario.js') }}"></script>
@endpush
