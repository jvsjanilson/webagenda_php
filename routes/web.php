<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaMontagemController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\LimiteController;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/agendas', AgendaController::class);
    Route::put('/agendas/{id}/done', [AgendaController::class, 'done'])->name('agendas.done');

    Route::resource('/agendamontagens', AgendaMontagemController::class);
    Route::put('/agendamontagens/{id}/done', [AgendaMontagemController::class, 'done'])->name('agendamontagens.done');

    Route::apiResource('/limites', LimiteController::class);
    Route::resource('/configs', ConfiguracaoController::class)->middleware('superuser');
});
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->middleware('auth');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('auth');
