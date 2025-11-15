<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empresa</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    {{-- jQuery (global) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <header>
        <div class="container">
            <h1>📊 Sistema de Encuestas</h1>
        </div>
    </header>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 40px auto;">
            <h2>Registro de Empresa</h2>
            <p style="color: #7f8c8d; margin-bottom: 20px;">Complete el formulario para registrar su empresa</p>

            <form>
                @csrf
                <div class="form-group">
                    <label for="nombre-empresa">Nombre de la Empresa *</label>
                    <input type="text" id="nombre-empresa" name="nombre-empresa" required>
                </div>

                <div class="form-group">
                    <label for="nit">NIT *</label>
                    <input type="text" id="nit" name="nit" required>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono">
                </div>

                <div class="form-group">
                    <label for="email-empresa">Correo Electrónico de la Empresa *</label>
                    <input type="email" id="email-empresa" name="email-empresa" required>
                </div>

                <h3 style="margin-top: 30px; margin-bottom: 15px; color: #2c3e50;">Datos del Usuario Administrador</h3>

                <div class="form-group">
                    <label for="nombre-user">Nombre Completo *</label>
                    <input type="text" id="nombre-user" name="nombre-user" required>
                </div>

                <div class="form-group">
                    <label for="email-user">Correo Electrónico *</label>
                    <input type="email" id="email-user" name="email-user" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirmar Contraseña *</label>
                    <input type="password" id="password-confirm" name="password-confirm" required>
                </div>

                <div class="form-group">
                    <label for="rol">Rol *</label>
                    <select id="rol" name="rol" required>
                        <option value="admin">Administrador</option>
                        <option value="user">Usuario</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-success" onclick="registroEmpresa(event)">Registrar
                        Empresa</button>
                    <a href="{{ '/' }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Encuestas. Todos los derechos reservados.</p>
    </footer>
    <script src="{{ asset('js/registroEmpresa/registroEmpresa.js') }}"></script>
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
