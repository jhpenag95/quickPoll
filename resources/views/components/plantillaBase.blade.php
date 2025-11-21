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

    {{-- DataTables CSS --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" /> --}}

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.3/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.bulma.css" />

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    {{-- Stack para CSS específicos por vista --}}
    @stack('styles')
</head>

<body>
    {{-- @auth permite renderizar el contenido solo si el usuario está autenticado --}}
    @auth
        <header>
            <div class="container_base">
                <h1>📊 Sistema de Encuestas</h1>
                <nav>
                    <ul>
                        <li><a href="{{ url('/dashboard') }}">Inicio</a></li>
                        <li><a href="{{ url('/encuestas') }}">Encuestas</a></li>
                        <li><a href="{{ url('/whatsapp') }}">Enviar por WhatsApp</a></li>
                        <li><a href="{{ url('/reportes') }}">Reportes</a></li>
                        <li><a href="{{ url('/usuarios') }}">Usuarios</a></li>
                        <li><a href="{{ url('/logout') }}">Cerrar Sesión</a></li>
                    </ul>
                </nav>
            </div>
        </header>
    @endauth

    <div class="container_base">
        @yield('content')
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Encuestas. Todos los derechos reservados.</p>
    </footer>

    @stack('scripts')
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.bulma.js"></script>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
