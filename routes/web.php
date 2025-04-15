<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\PrincipalController;
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

Route::get('folios/show', [FolioController::class, 'show'])->name('folios.show')->middleware('auth');
Route::get('folios/crear', [FolioController::class, 'create'])->name('folios.create')->middleware('auth');
Route::post('folios/store', [FolioController::class, 'store'])->name('folios.store')->middleware('auth');
Route::get('folios/paginate', [FolioController::class, 'paginate'])->name('folios.paginate')->middleware('auth');
Route::get('folios/{folio}', [FolioController::class, 'detail'])->name('folios.detail')->middleware('auth');
Route::get('folios/{folio}/edit', [FolioController::class, 'edit'])->name('folios.edit')->middleware('auth');
Route::put('folios/{folio}', [FolioController::class, 'update'])->name('folios.update')->middleware('auth');
