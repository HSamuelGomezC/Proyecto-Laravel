<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PresupuestoController;

Route::resource('presupuestos', PresupuestoController::class);
Route::post('/presupuestos/{id}/abonar', [PresupuestoController::class, 'abonar'])->name('presupuestos.abonar');
