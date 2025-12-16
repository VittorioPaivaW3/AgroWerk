<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\SetoresController;
use App\Http\Controllers\ManutencaoPreventivaController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Rotas REST de OS, Clientes e Equipamentos
    Route::resource('ordens', OrdemServicoController::class);
    Route::resource('setores', SetoresController::class)
        ->parameters(['setores' => 'setor'])
        ->except(['show']);
    Route::resource('equipamentos', EquipamentoController::class)
        ->parameters(['equipamentos' => 'equipamento']);
    Route::resource('ordens', OrdemServicoController::class);

    // 🔧 Manutenções preventivas

    // Página com calendário + lista + modal
    Route::get('/manutencoes-preventivas', [ManutencaoPreventivaController::class, 'index'])
        ->name('manutencoes.preventivas.index');

    // Eventos para o calendário
    Route::get('/manutencoes-preventivas/events', [ManutencaoPreventivaController::class, 'events'])
        ->name('manutencoes.preventivas.events');

    // Criar (modal)
    Route::post('/manutencoes-preventivas', [ManutencaoPreventivaController::class, 'store'])
        ->name('manutencoes.preventivas.store');

    // Ver uma manutenção específica
    Route::get('/manutencoes-preventivas/{manutencaoPreventiva}', [ManutencaoPreventivaController::class, 'show'])
        ->name('manutencoes.preventivas.show');

    // Editar
    Route::get('/manutencoes-preventivas/{manutencaoPreventiva}/edit', [ManutencaoPreventivaController::class, 'edit'])
        ->name('manutencoes.preventivas.edit');

    // Atualizar
    Route::put('/manutencoes-preventivas/{manutencaoPreventiva}', [ManutencaoPreventivaController::class, 'update'])
        ->name('manutencoes.preventivas.update');

    // Marcar como concluída
    Route::patch('/manutencoes-preventivas/{manutencaoPreventiva}/concluir', [ManutencaoPreventivaController::class, 'concluir'])
        ->name('manutencoes.preventivas.concluir');

    // Excluir
    Route::delete('/manutencoes-preventivas/{manutencaoPreventiva}', [ManutencaoPreventivaController::class, 'destroy'])
        ->name('manutencoes.preventivas.destroy');
});

require __DIR__.'/auth.php';
