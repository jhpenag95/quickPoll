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

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Pérez</td>
                    <td>juan.perez@empresa.com</td>
                    <td>Administrador</td>
                    <td><span class="badge badge-active">Activo</span></td>
                    <td>2024-01-15</td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/usuarios/editarUsuario') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Desactivar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>María González</td>
                    <td>maria.gonzalez@empresa.com</td>
                    <td>Analista</td>
                    <td><span class="badge badge-active">Activo</span></td>
                    <td>2024-02-20</td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/usuarios/editarUsuario') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Desactivar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Carlos Rodríguez</td>
                    <td>carlos.rodriguez@empresa.com</td>
                    <td>Creador</td>
                    <td><span class="badge badge-active">Activo</span></td>
                    <td>2024-03-10</td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/usuarios/editarUsuario') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Desactivar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Ana Martínez</td>
                    <td>ana.martinez@empresa.com</td>
                    <td>Analista</td>
                    <td><span class="badge badge-inactive">Inactivo</span></td>
                    <td>2024-01-25</td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/usuarios/editarUsuario') }}" class="btn">Editar</a>
                            <button class="btn btn-success">Activar</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')

@endpush
