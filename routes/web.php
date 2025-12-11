<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CadasMaqController;
use App\Http\Controllers\OrdemServicoController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Rotas REST de OS, Clientes e Equipamentos
    Route::resource('ordens', OrdemServicoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('equipamentos', EquipamentoController::class);

    Route::get('/cadastro/cadasmaq', [CadasMaqController::class, 'index'])
        ->name('cadasmaq');
    Route::resource('ordens', OrdemServicoController::class);

});

require __DIR__.'/auth.php';
