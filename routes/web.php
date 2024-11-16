<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\DoctorController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('usuarios', UserController::class);
Route::middleware(['role:admin'])->group(function () {
    Route::get('usuarios/eliminados', [UserController::class, 'trashed'])->name('usuarios.trashed');
    Route::post('usuarios/{id}/restaurar', [UserController::class, 'restore'])->name('usuarios.restore');
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermissionController::class);
});

Route::middleware(['role:admin|doctor'])->group(function () {
    Route::resource('doctores', DoctorController::class);
});

Route::middleware(['role:paciente|admin|doctor'])->group(function () {
    Route::resource('citas', QuoteController::class);
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
