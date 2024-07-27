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


Auth::routes();

Route::middleware(['auth'])->group(function() {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/agendas', AgendaController::class);
    Route::resource('/agendamontagens', AgendaMontagemController::class);
    Route::put('/agendamontagens/{id}/done', [AgendaMontagemController::class, 'done'])->name('agendamontagens.done');
    Route::apiResource('/limites', LimiteController::class);
    Route::resource('/configs', ConfiguracaoController::class)->middleware('superuser');
});
