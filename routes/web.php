<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('log');

Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

Route::get('inicio', [PrincipalController::class, 'inicio'])->name('principal.inicio')->middleware('auth');
Route::get('users/', [UserController::class, 'show'])->name('user.show')->middleware('auth');

Route::get('solicitudes/show', [SolicitudController::class, 'show'])->name('solicitudes.show')->middleware('auth');
Route::get('solicitudes/paginate', [SolicitudController::class, 'paginate'])->name('solicitudes.paginate')->middleware('auth');
Route::get('solicitudes/crear', [SolicitudController::class, 'create'])->name('solicitudes.create')->middleware('auth');
Route::post('solicitudes/store', [SolicitudController::class, 'store'])->name('solicitudes.store')->middleware('auth');
Route::get('solicitudes/{solicitud}/edit', [SolicitudController::class, 'edit'])->name('solicitudes.edit')->middleware('auth');
Route::put('solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('solicitudes.update')->middleware('auth');
Route::get('solicitudes/{solicitud}/success', [SolicitudController::class, 'success'])->name('solicitudes.success')->middleware('auth');

Route::get('areas/show', [AreaController::class, 'show'])->name('areas.show')->middleware('auth');
Route::get('areas/crear', [AreaController::class, 'create'])->name('areas.create')->middleware('auth');
Route::post('areas/store', [AreaController::class, 'store'])->name('areas.store')->middleware('auth');
Route::get('areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit')->middleware('auth');
Route::put('areas/{area}', [AreaController::class, 'update'])->name('areas.update')->middleware('auth');
Route::patch('areas/{area}/toggle-estatus', [AreaController::class, 'toggleEstatus'])->name('areas.toggleEstatus')->middleware('auth');

Route::get('reportes/fechas', [ReporteController::class, 'fechas'])->name('reportes.fechas')->middleware('auth');
