<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

    {{-- jQuery (global) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Stack para CSS específicos por vista --}}
    @stack('styles')
</head>

<body>
    <header>
        <div class="container_base">
            <h1>📊 Sistema de Encuestas</h1>
            <nav>
                <ul>
                    <li><a href="{{ url('/dashboard') }}">Inicio</a></li>
                    <li><a href="{{ url('/encuestas') }}">Encuestas</a></li>
                    <li><a href="{{ url('/reportes') }}">Reportes</a></li>
                    <li><a href="{{ url('/usuarios') }}">Usuarios</a></li>
                    <li><a href="{{ url('/logout') }}">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container_base">
        @yield('content')
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Encuestas. Todos los derechos reservados.</p>
    </footer>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
