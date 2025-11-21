@extends('components.plantillaBase')

@section('styles')
@endsection

@section('content')
    <div class="card" style="max-width: 600px; margin: 40px auto;">
        <h2>Editar Usuario Autorizado</h2>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Actualice la información del usuario</p>

        <form action="{{ route('usuarios.update', ['id' => $user->id]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico *</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol *</label>
                <select id="rol" name="rol" required>
                    <option value="administrador" {{ strtolower($user->rol) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="creador" {{ strtolower($user->rol) === 'creador' ? 'selected' : '' }}>Creador de Encuestas</option>
                    <option value="analista" {{ strtolower($user->rol) === 'analista' ? 'selected' : '' }}>Analista</option>
                </select>
            </div>

            <div class="form-group">
                <label for="estado">Estado *</label>
                <select id="estado" name="estado" required>
                    <option value="ACTIVO" {{ strtoupper($user->estado) === 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                    <option value="INACTIVO" {{ strtoupper($user->estado) === 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña (opcional)</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="form-group">
                <label for="idEmpresa">Empresa (opcional)</label>
                <input type="number" id="idEmpresa" name="idEmpresa" value="{{ $user->empresa_id }}">
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="{{ route('usuarios') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
@endsection