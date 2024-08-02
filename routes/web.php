<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaMontagemController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\LimiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagamentoController;


Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::middleware(['auth', 'licenca', 'montador', 'entregador'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('/agendas', AgendaController::class);
    Route::post('/agendas/{id}/done', [AgendaController::class, 'done'])->name('agendas.done');
    Route::get('/agendas/{id}/entregue', [AgendaController::class, 'entregue'])->name('agendas.entregue');
    Route::get('/agendas/{id}/images', [AgendaController::class, 'images'])->name('agendas.images');

    Route::resource('/agendamontagens', AgendaMontagemController::class);
    Route::post('/agendamontagens/{id}/done', [AgendaMontagemController::class, 'done'])->name('agendamontagens.done');
    Route::get('/agendamontagens/{id}/entregue', [AgendaMontagemController::class, 'entregue'])->name('agendamontagens.entregue');
    Route::get('/agendamontagens/{id}/images', [AgendaMontagemController::class, 'images'])->name('agendamontagens.images');

    Route::apiResource('/limites', LimiteController::class);
    Route::resource('/configs', ConfiguracaoController::class)->middleware('superuser');
});

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->middleware('auth');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('auth');

Route::get('/negado', function() {
    return view('licenca.acesso_negado');
})->name('acesso.negado');

Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index')->middleware('auth');
Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store')->middleware('auth');
Route::get('/pagamentos/{documento}/status', [PagamentoController::class, 'status'])->name('pagamentos.status')->middleware('auth');
Route::get('/pagamentos/create', [PagamentoController::class, 'create'])->name('pagamentos.create')->middleware('auth');

