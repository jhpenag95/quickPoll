<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\RegistroEmpresaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\encuestasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\LoginController;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/whatsapp', [encuestasController::class, 'whatsapp_encuesta'])->name('whatsapp');



Route::get('/empresa', [RegistroEmpresaController::class, 'registroEmpresa'])->name('registroEmpresa');
Route::post('/empresa/registrar', [RegistroEmpresaController::class, 'store']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios');
Route::post('/usuario/registrar', [UserController::class, 'store'])->name('usuario.registrar');
Route::get('/usuarios/listar', [UserController::class, 'listar'])->name('usuarios.listar');
Route::get('/usuarios/editar/{id}', [UserController::class, 'editar'])->name('usuarios.editar');
Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');



Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes');
Route::get('/reportes/encuestas', [ReportesController::class, 'encuestasEmpresa'])->name('reportes.encuestas');
Route::get('/reportes/generar', [ReportesController::class, 'generar'])->name('reportes.generar');
Route::get('/reportes/excel', [ReportesController::class, 'excel'])->name('reportes.excel');
Route::get('/reportes/pdf', [ReportesController::class, 'pdf'])->name('reportes.pdf');

Route::get('/encuestas', [encuestasController::class, 'index'])->name('encuestas');
Route::get('/encuestas/crearEncuesta', [encuestasController::class, 'crearEncuesta'])->name('crearEncuesta');
Route::get('/encuestas/listarEncuestas', [encuestasController::class, 'listarEncuestas'])->name('listarEncuestas');
Route::get('/encuestas/editarEncuesta/{id}', [encuestasController::class, 'editarEncuesta'])->name('editarEncuesta');
Route::get('/encuestas/ver/{id}', [encuestasController::class, 'verEncuesta'])->name('encuestas.ver');
Route::get('/encuestas/ver/{id}', [encuestasController::class, 'verEncuesta'])->name('verEncuesta');



// Crear encuesta
Route::post('/encuestas', [encuestasController::class, 'store'])->name('encuestas.store');

// Actualizar encuesta
Route::put('/encuestas/{id}', [encuestasController::class, 'update'])->name('encuestas.update');

// Enlace público largo
Route::get('/encuesta/{id}', [encuestasController::class, 'public'])->name('encuestas.public');

// Responder encuesta pública
Route::post('/encuesta/{id}/responder', [encuestasController::class, 'responder'])->name('encuestas.responder');

// Página de agradecimiento
Route::get('/encuesta/{id}/gracias', [encuestasController::class, 'gracias'])->name('encuestas.gracias');

// Enlace corto que redirige al largo
Route::get('/s/{code}', [encuestasController::class, 'short'])->name('encuestas.short');

// Route::get('/agregar-encuesta', [IndexController::class, 'agregar-encuesta'])->name('agregar-encuesta');

Route::get('/agregar-usuario', [UserController::class, 'agregar_usuario'])->name('agregar-usuario');
Route::get('/encuesta/{id}/whatsapp', [encuestasController::class, 'whatsapp'])->name('encuestas.whatsapp');
Route::post('/encuesta/{id}/whatsapp/enviar', [encuestasController::class, 'enviarWhatsapp'])->name('encuestas.whatsapp.enviar');
Route::get('/qr/{id}', [encuestasController::class, 'qr'])->name('encuestas.qr');

