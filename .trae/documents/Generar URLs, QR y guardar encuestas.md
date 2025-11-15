## Objetivo
- Guardar encuestas en `encuestas` con `nombre`, `descripcion`, `fechaInicio`, `fechaFin`, `estado`, `enlaceLargo`, `enlaceCorto`, `codigoQR`, `idEmpresa`, `user_id`.
- Generar un enlace largo estable para la encuesta y uno corto que redirige al largo.
- Generar y almacenar un código QR del enlace largo.

## Qué falta ahora
- El formulario usa `action="encuestas.html"` y no envía a Laravel (`resources/views/encuestas/crearEncuesta.blade.php:15`).
- El botón "Guardar Encuesta" llama a `guardarEncuesta()` pero la función no persiste nada (`public/js/encuestas/crearEncuesta.js:142-149`).
- No hay rutas `POST` para crear, ni rutas públicas para mostrar/redirigir.
- `enlacesDeDistribucion.js` está vacío.

## Plan técnico
### 1) Rutas
- Definir rutas en `routes/web.php`:
  - `GET /encuestas/crear` → `encuestasController@crearEncuesta` (ya existe la vista).
  - `POST /encuestas` (nombre: `encuestas.store`) → `encuestasController@store`.
  - `GET /encuesta/{id}` (nombre: `encuestas.public`) → mostrar la encuesta pública por `id`.
  - `GET /s/{code}` (nombre: `encuestas.short`) → buscar la encuesta por `code` y redirigir al enlace largo.

### 2) Controlador: validación, guardado y generación
- Agregar método `store(Request $request)` en `app/Http/Controllers/encuestasController.php`:
  - Validar: `nombre` requerido, `fechaInicio` y `fechaFin` formato fecha y `fechaInicio <= fechaFin`.
  - Determinar `estado`: "activa" al guardar; si se pulsa "Guardar como Borrador", usar "borrador".
  - Crear registro `Encuestas` con los campos básicos (`nombre`, `descripcion`, `fechaInicio`, `fechaFin`, `estado`, `idEmpresa`, `user_id`).
  - Generar enlace largo: `route('encuestas.public', ['id' => $encuesta->id])` y guardar en `enlaceLargo`.
  - Generar `code` corto (6–8 caracteres alfanuméricos) y construir `enlaceCorto` como `config('app.url') . '/s/' . $code`.
  - Generar QR (PNG) del enlace largo y guardar el archivo en `storage/app/public/qrcodes/encuesta_{id}.png`; guardar la ruta pública en `codigoQR`.
  - Guardar cambios y redirigir a una página de confirmación (o a la vista de edición) mostrando los enlaces y el QR.

### 3) Biblioteca de QR
- Verificar si está instalada una librería de QR. Si no, instalar `simplesoftwareio/simple-qrcode` y usar `\SimpleSoftwareIO\QrCode\Facades\QrCode` para generar el PNG.
- Asegurar que el disco `public` esté enlazado (`php artisan storage:link`).

### 4) Vista del formulario
- En `resources/views/encuestas/crearEncuesta.blade.php`:
  - Cambiar `action` a `{{ route('encuestas.store') }}` y `method="POST"`.
  - Incluir `@csrf`.
  - Usar `name` que coincidan con el modelo: `nombre`, `descripcion`, `fechaInicio`, `fechaFin`.
  - El botón "Guardar como Borrador" enviará `estado=borrador` (por ejemplo, con `name="estado" value="borrador"`).

### 5) Frontend para preguntas (mínimo viable)
- Mantener la UI dinámica actual (`public/js/encuestas/crearEncuesta.js`).
- En `guardarEncuesta(event)`, construir un JSON con las preguntas y opciones y ponerlo en un `input hidden` `name="preguntas"` para que el backend pueda almacenarlo más adelante (si se decide persistir preguntas). Por ahora, el foco es guardar los campos del modelo y generar enlaces/QR.
- Enviar el formulario normalmente tras preparar ese `hidden`.

### 6) Redirección de enlace corto
- En el método que atiende `GET /s/{code}` construir el enlace completo (`config('app.url') . '/s/' . $code`), buscar la encuesta cuyo `enlaceCorto` sea exactamente ese valor y redirigir (`redirect()->to($encuesta->enlaceLargo)`).

### 7) Presentación de enlaces y QR
- Tras guardar, mostrar Enlace Largo (`enlaceLargo`), Enlace Corto (`enlaceCorto`) y el QR (`<img src="/storage/qrcodes/encuesta_{id}.png">`).

## Ejemplos de implementación
### Generación de código corto
```php
$code = Str::upper(Str::random(6));
$short = config('app.url') . '/s/' . $code;
```

### Generación de QR
```php
$png = QrCode::format('png')->size(300)->generate($encuesta->enlaceLargo);
Storage::disk('public')->put("qrcodes/encuesta_{$encuesta->id}.png", $png);
$encuesta->codigoQR = '/storage/qrcodes/encuesta_' . $encuesta->id . '.png';
$encuesta->save();
```

## Referencias en tu código
- Formulario que se debe cambiar: `resources/views/encuestas/crearEncuesta.blade.php:15` y botón submit `:105`.
- JS que hoy no guarda: `public/js/encuestas/crearEncuesta.js:142-149`.
- Controlador donde se añadirá `store`: `app/Http/Controllers/encuestasController.php:14-17` (junto a `crearEncuesta`).
- Modelo ya listo con fillables: `app/Models/Encuestas.php:12-26`.

## Verificación
- Probar guardado con fechas válidas e inválidas; confirmar que `encuestas` recibe los valores.
- Confirmar que `enlaceLargo` abre la encuesta y que `enlaceCorto` redirige al largo.
- Verificar que el archivo PNG se crea en `public/storage/qrcodes/` y se muestra en la vista.

¿Confirmas que avancemos con estos cambios?