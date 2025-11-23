<h1 align="center">QuickPoll</h1>

Sistema de encuestas multiempresa desarrollado con Laravel 12 y PHP 8.2. Permite registrar empresas, crear y distribuir encuestas públicas mediante enlaces y códigos QR, recolectar respuestas, generar reportes (CSV y PDF) y enviar invitaciones por WhatsApp.

## Requisitos

- PHP 8.2+
- Composer 2+
- Node.js 20+ y npm
- SQLite, MySQL o PostgreSQL (por defecto SQLite)

## Instalación

- Clonar el repositorio.
- Ejecutar `composer install`.
- Copiar `.env.example` a `.env` y completar variables.
- Generar clave de aplicación con `php artisan key:generate`.
- Ejecutar migraciones con `php artisan migrate`.
- Instalar dependencias de frontend con `npm install`.
- Construir assets opcionales con `npm run build`.

También puedes usar el script automatizado: `composer setup`.

## Configuración

- Configurar `APP_URL` para que los enlaces públicos funcionen correctamente.
- Base de datos: por defecto `DB_CONNECTION=sqlite`. Puedes cambiar a MySQL/PostgreSQL.
- Sesiones y colas usan base de datos (`SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`).
- Integración de WhatsApp:
  - `WHATSAPP_TOKEN`: token del Business API.
  - `WHATSAPP_PHONE_NUMBER_ID`: identificador del número.

## Ejecución en desarrollo

- Servidor de desarrollo y procesos auxiliares: `composer dev`.
- Alternativa simple: `php artisan serve`.

## Funcionalidades

- Registro de empresas y creación de usuario inicial.
- Autenticación de usuarios y gestión de sesión.
- Dashboard con métricas por empresa.
- CRUD de usuarios y encuestas internas.
- Enlace público de encuesta, enlace corto y código QR.
- Recolección de respuestas con control por IP/sesión y registro de visitas únicas.
- Reportes en CSV y PDF.
- Envío de invitaciones por WhatsApp.

## Rutas principales

- Inicio y autenticación:
  - `GET /` y `GET /login`: formulario de login (`routes/web.php:15`).
  - `POST /login`: autenticar (`routes/web.php:16`, `app/Http/Controllers/LoginController.php:19`).
  - `GET /logout`: cerrar sesión (`routes/web.php:17`).
- Registro de empresa:
  - `GET /empresa`: formulario (`routes/web.php:30`).
  - `POST /empresa/registrar`: registrar (`routes/web.php:31`, `app/Http/Controllers/RegistroEmpresaController.php:17`).
- Dashboard:
  - `GET /dashboard`: vista (`routes/web.php:37`).
  - `GET /api/dashboard/statistics`: API (`routes/web.php:20`, `app/Http/Controllers/DashboardController.php:17`).
- Usuarios:
  - `GET /usuarios`, `GET /agregar-usuario`, `GET /usuarios/listar` (`routes/web.php:43-46`).
  - `POST /usuario/registrar`, `PUT /usuarios/{id}`, `GET /usuarios/eliminar/{id}` (`routes/web.php:44,48`).
- Encuestas (interno):
  - `GET /encuestas`: listado (`routes/web.php:64`).
  - `GET /encuestas/crearEncuesta`, `GET /encuestas/editarEncuesta/{id}`, `GET /encuestas/eliminar/{id}` (`routes/web.php:65,67,68`).
  - `GET /encuestas/listarEncuestas`: API (`routes/web.php:66`, `app/Http/Controllers/encuestasController.php:36`).
  - `POST /encuestas`: crear (`routes/web.php:73`, `app/Http/Controllers/encuestasController.php:129`).
  - `PUT /encuestas/{id}`: actualizar (`routes/web.php:76`, `app/Http/Controllers/encuestasController.php:229`).
- Encuesta pública:
  - `GET /encuesta/{id}`: ver y responder (`routes/web.php:79`, `app/Http/Controllers/encuestasController.php:309`).
  - `POST /encuesta/{id}/responder`: guardar respuestas (`routes/web.php:82`, `app/Http/Controllers/encuestasController.php:379`).
  - `GET /encuesta/{id}/gracias`: agradecimiento (`routes/web.php:85`, `app/Http/Controllers/encuestasController.php:465`).
  - `GET /s/{code}`: enlace corto (`routes/web.php:88`, `app/Http/Controllers/encuestasController.php:365`).
  - `GET /qr/{id}`: QR SVG (`routes/web.php:97`, `app/Http/Controllers/encuestasController.php:116`).
- WhatsApp:
  - `GET /encuesta/{id}/whatsapp`: vista (`routes/web.php:95`, `app/Http/Controllers/encuestasController.php:475`).
  - `POST /encuesta/{id}/whatsapp/enviar`: envío (`routes/web.php:96`, `app/Http/Controllers/encuestasController.php:491`).
- Reportes:
  - `GET /reportes`: vista (`routes/web.php:55`).
  - `GET /reportes/encuestas`: encuestas de empresa (`routes/web.php:56`).
  - `GET /reportes/generar`: datos (`routes/web.php:57`, `app/Http/Controllers/ReportesController.php:32`).
  - `GET /reportes/excel`: CSV (`routes/web.php:58`, `app/Http/Controllers/ReportesController.php:44`).
  - `GET /reportes/pdf`: PDF (`routes/web.php:59`, `app/Http/Controllers/ReportesController.php:100`).

## Modelos y base de datos

- `empresas` (`database/migrations/2025_11_13_000001_create_empresa_table.php`): datos básicos de empresa.
- `users` (`2025_11_13_000002_create_users_table.php`): usuarios con `empresa_id`.
- `encuestas` (`2025_11_13_000003_create_encuestas_table.php`): metadatos y enlaces.
- `preguntas` (`2025_11_13_000005_create_preguntas_table.php`): texto, tipo y orden.
- `opcionesrespuesta` (`2025_11_13_000004_create_opcionesrespuesta_table.php`): opciones para preguntas.
- `respuesta_encuesta` (`2025_11_13_000006_create_respuesta_encuesta_table.php`): respuesta por IP/canal.
- `detalle_respuesta` (`2025_11_13_000007_create_detalle_respuesta_table.php`): detalle por pregunta/opción.
- `encuesta_visitas` (`2025_11_20_000002_create_encuesta_visitas_table.php`): visitas únicas por IP.
- `reporte` (`2025_11_13_000008_create_reporte_table.php`): auditoría de reportes.

Relaciones principales:

- Usuario pertenece a Empresa (`app/Models/User.php:56`).
- Empresa tiene muchos Usuarios (`app/Models/registroEmpresa.php:24`).
- Encuesta pertenece a Empresa y Usuario (`2025_11_13_000003_create_encuestas_table.php:26-31`).

## Flujo de encuestas

- Creación: se registran preguntas y, si son de opción múltiple, se crean sus opciones (`app/Http/Controllers/encuestasController.php:172-220`).
- Generación de enlaces: se crea enlace largo, corto y QR usando `APP_URL` (`app/Http/Controllers/encuestasController.php:152-166`).
- Publicación: evita respuestas duplicadas por sesión/IP y registra visita única (`app/Http/Controllers/encuestasController.php:314-339`).
- Respuesta: guarda detalle por tipo de pregunta y duración si disponible (`app/Http/Controllers/encuestasController.php:395-456`).

## Reportes

- API arma resumen por pregunta y métricas generales: promedio en escala, tasa de respuesta y tiempo promedio (`app/Http/Controllers/ReportesController.php:137-335`).
- Exportación CSV mediante streaming (`app/Http/Controllers/ReportesController.php:44-98`).
- Exportación PDF con DomPDF (`app/Http/Controllers/ReportesController.php:100-111`).

## Integración WhatsApp

- Requiere `WHATSAPP_TOKEN` y `WHATSAPP_PHONE_NUMBER_ID` válidos.
- Envío a múltiples números con limpieza y deduplicación (`app/Http/Controllers/encuestasController.php:503-507`).
- Llama Graph API v20.0 y muestra conteo de enviados/fallidos (`app/Http/Controllers/encuestasController.php:520-529`).

## Frontend

- Vistas Blade en `resources/views` con plantilla base `components/plantillaBase.blade.php`.
- Estilos en `public/css/global.css` y CSS específicos por módulo (`public/css/...`).
- JS por vista en `public/js/...`.
- Vite y Tailwind están configurados; las vistas actuales cargan CSS/JS desde `public`. Puedes migrar a `@vite` si lo deseas (`vite.config.js`).

## Pruebas

- Ejecutar `composer test`.
- Base de datos de pruebas en memoria (`phpunit.xml`).
- Prueba de integración WhatsApp usa `Http::fake` y valida redirección y estado (`tests/Feature/WhatsAppIntegrationTest.php`).

## Despliegue

- Configurar `APP_ENV=production`, `APP_DEBUG=false` y `APP_URL` real.
- Configurar base de datos y ejecutar `php artisan migrate --force`.
- Ejecutar un worker de colas si usas jobs (`QUEUE_CONNECTION=database`).
- Configurar servidor web para servir `public/`.

## Dependencias principales

- Laravel 12 (`composer.json`).
- Bacon QR Code para generar SVGs.
- Barryvdh DomPDF para exportar PDF.
- laravel-vite-plugin y Tailwind CSS.

## Comandos útiles

- `composer setup`: instalación y build completo.
- `composer dev`: server, colas, logs y Vite en paralelo.
- `php artisan migrate`: crea/actualiza tablas.
- `php artisan serve`: servidor local.
