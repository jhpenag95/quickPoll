<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\RegistroEmpresaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\encuestasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\LoginController;

// Página de inicio y autenticación
Route::get('/', [IndexController::class, 'index'])->name('index'); // Muestra login o landing
Route::get('/login', [IndexController::class, 'index'])->name('login.form'); // Formulario de login (GET)
Route::post('/login', [LoginController::class, 'login'])->name('login'); // Autenticar credenciales (POST)
Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); // Cerrar sesión

//api
Route::get('/api/dashboard/statistics', [DashboardController::class, 'statistics'])->name('api.dashboard.statistics'); // Obtener estadísticas

// Vistas abiertas para invitados (si aplica)
Route::middleware('guest')->group(function () {
    Route::get('/whatsapp', [encuestasController::class, 'whatsapp_encuesta'])->name('whatsapp'); // Vista informativa de WhatsApp
});


// Registro de empresa (público)
Route::middleware('guest')->group(function () {
    Route::get('/empresa', [RegistroEmpresaController::class, 'registroEmpresa'])->name('registroEmpresa'); // Formulario de registro de empresa
    Route::post('/empresa/registrar', [RegistroEmpresaController::class, 'store']); // Procesar registro
});


// Dashboard (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Panel principal
});


// Gestión de usuarios (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios'); // Listado y acciones
    Route::post('/usuario/registrar', [UserController::class, 'store'])->name('usuario.registrar'); // Alta usuario
    Route::get('/usuarios/listar', [UserController::class, 'listar'])->name('usuarios.listar'); // API tabla usuarios
    Route::get('/usuarios/editar/{id}', [UserController::class, 'editar'])->name('usuarios.editar'); // Editar usuario (vista)
    Route::get('/usuarios/eliminar/{id}', [UserController::class, 'eliminar'])->name('usuarios.eliminar'); // Eliminar usuario (vista)
    Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update'); // Guardar edición
});



// Reportes (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes'); // Vista de reportes
    Route::get('/reportes/encuestas', [ReportesController::class, 'encuestasEmpresa'])->name('reportes.encuestas'); // API: encuestas de la empresa
    Route::get('/reportes/generar', [ReportesController::class, 'generar'])->name('reportes.generar'); // API: datos del reporte
    Route::get('/reportes/excel', [ReportesController::class, 'excel'])->name('reportes.excel'); // Exportar CSV
    Route::get('/reportes/pdf', [ReportesController::class, 'pdf'])->name('reportes.pdf'); // Exportar PDF
});

// Gestión de encuestas internas (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/encuestas', [encuestasController::class, 'index'])->name('encuestas'); // Vista listado
    Route::get('/encuestas/crearEncuesta', [encuestasController::class, 'crearEncuesta'])->name('crearEncuesta'); // Vista crear
    Route::get('/encuestas/listarEncuestas', [encuestasController::class, 'listarEncuestas'])->name('listarEncuestas'); // API tabla encuestas
    Route::get('/encuestas/editarEncuesta/{id}', [encuestasController::class, 'editarEncuesta'])->name('editarEncuesta'); // Vista editar
    Route::get('/encuestas/eliminar/{id}', [encuestasController::class, 'eliminarEncuesta'])->name('eliminarEncuesta'); // Vista eliminar
    Route::get('/encuestas/ver/{id}', [encuestasController::class, 'verEncuesta'])->name('encuestas.ver'); // Vista detalle
});

// Crear encuesta (guardar)
Route::post('/encuestas', [encuestasController::class, 'store'])->name('encuestas.store');

// Actualizar encuesta (guardar)
Route::put('/encuestas/{id}', [encuestasController::class, 'update'])->name('encuestas.update');

// Enlace público de la encuesta (acceso público)
Route::get('/encuesta/{id}', [encuestasController::class, 'public'])->name('encuestas.public');

// Enviar respuestas de encuesta pública
Route::post('/encuesta/{id}/responder', [encuestasController::class, 'responder'])->name('encuestas.responder');

// Página de agradecimiento post-respuesta
Route::get('/encuesta/{id}/gracias', [encuestasController::class, 'gracias'])->name('encuestas.gracias');

// Enlace corto que redirige al enlace público
Route::get('/s/{code}', [encuestasController::class, 'short'])->name('encuestas.short');



// Integraciones y utilidades (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/agregar-usuario', [UserController::class, 'agregar_usuario'])->name('agregar-usuario'); // Vista alta usuario
    Route::get('/whatsapp', [encuestasController::class, 'whatsapp_encuesta'])->name('whatsapp'); // Vista informativa de WhatsApp (autenticado)
    Route::get('/encuesta/{id}/whatsapp', [encuestasController::class, 'whatsapp'])->name('encuestas.whatsapp'); // Vista enviar por WhatsApp
    Route::post('/encuesta/{id}/whatsapp/enviar', [encuestasController::class, 'enviarWhatsapp'])->name('encuestas.whatsapp.enviar'); // Envío WhatsApp
    Route::get('/qr/{id}', [encuestasController::class, 'qr'])->name('encuestas.qr'); // Generar QR (SVG) del enlace público
});
