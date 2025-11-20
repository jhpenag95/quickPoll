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



Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes');

Route::get('/encuestas', [encuestasController::class, 'index'])->name('encuestas');
Route::get('/encuestas/crearEncuesta', [encuestasController::class, 'crearEncuesta'])->name('crearEncuesta');

// Crear encuesta
Route::post('/encuestas', [encuestasController::class, 'store'])->name('encuestas.store');

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

