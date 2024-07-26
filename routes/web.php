<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaMontagemController;
use App\Http\Controllers\ConfiguracaoController;

Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::resource('/agendas', AgendaController::class);
Route::resource('/agendamontagens', AgendaMontagemController::class);
Route::put('/agendamontagens/{id}/done', [AgendaMontagemController::class, 'done'])->name('agendamontagens.done');
Route::resource('/configs', ConfiguracaoController::class);
