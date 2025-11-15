@extends('components.plantillaBase')

@push('styles')

@endpush
<title>Encuestas</title>

@section('content')
    <h2 style="margin-top: 30px;">Gestión de Encuestas</h2>
    <p style="color: #7f8c8d; margin-bottom: 20px;">Crea, modifica y administra tus encuestas</p>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Mis Encuestas</h3>
            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn btn-success">+ Nueva Encuesta</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre de la Encuesta</th>
                    <th>Fecha Creación</th>
                    <th>Vigencia</th>
                    <th>Respuestas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Satisfacción del Cliente - Soporte Telefónico</td>
                    <td>2024-11-10</td>
                    <td>2024-11-10 / 2024-12-10</td>
                    <td>45</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Satisfacción con Envío de Productos</td>
                    <td>2024-11-09</td>
                    <td>2024-11-09 / 2024-12-09</td>
                    <td>32</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Asistencia a Capacitaciones</td>
                    <td>2024-11-08</td>
                    <td>2024-11-08 / 2024-11-30</td>
                    <td>23</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Percepción de Aseo en Espacios</td>
                    <td>2024-11-05</td>
                    <td>2024-11-05 / 2024-11-10</td>
                    <td>67</td>
                    <td><span class="badge badge-inactive">Finalizada</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Necesidades de Capacitación</td>
                    <td>2024-11-01</td>
                    <td>2024-11-01 / 2024-11-15</td>
                    <td>89</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Satisfacción de Empleados - Capacitaciones</td>
                    <td>2024-10-28</td>
                    <td>2024-10-28 / 2024-11-28</td>
                    <td>54</td>
                    <td><span class="badge badge-active">Activa</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('/encuestas/verEncuesta') }}" class="btn">Ver</a>
                            <a href="{{ url('/encuestas/crearEncuesta') }}" class="btn">Editar</a>
                            <button class="btn btn-secondary">Duplicar</button>
                            <button class="btn btn-danger">Eliminar</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
