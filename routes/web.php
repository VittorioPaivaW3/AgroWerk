<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\SetoresController;
use App\Http\Controllers\ManutencaoPreventivaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TecnicoDashboardController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\DashboardVisualizadorController;
use App\Http\Controllers\RelatorioController;

// Redireciona raiz
Route::get('/', function () {
    // Se estiver logado como visualizador, manda pro painel dele
    if (auth()->check() && auth()->user()->hasRole('visualizador')) {
        return redirect()->route('dashboard.visualizador');
    }
    elseif (auth()->check() && auth()->user()->hasRole('tecnico')) {
        return redirect()->route('tecnico.dashboard');
    }
    // Senão, manda pro dashboard padrão (admin/gestor/técnico, etc.)
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    /*
     |------------------------------------------------------------------
     | DASHBOARD PADRÃO
     |------------------------------------------------------------------
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin|gestor')
        ->name('dashboard');

    /*
     |------------------------------------------------------------------
     | USUÁRIOS
     |------------------------------------------------------------------
     */
    Route::resource('usuarios', UsuarioController::class)
        ->middleware('role:admin|gestor')
        ->parameters(['usuarios' => 'usuario'])
        ->except(['show']);

    /*
     |------------------------------------------------------------------
     | ORDENS DE SERVIÇO
     |------------------------------------------------------------------
     */
    Route::resource('ordens', OrdemServicoController::class)
        ->middleware('role:admin|gestor|visualizador')
        ->parameters(['ordens' => 'orden'])
        ->except(['show', 'edit', 'update']);

    Route::get('/ordens/{orden}', [OrdemServicoController::class, 'show'])
        ->middleware('role:admin|gestor|visualizador|tecnico')
        ->name('ordens.show');

    Route::get('/ordens/{orden}/edit', [OrdemServicoController::class, 'edit'])
        ->middleware('role:admin')
        ->name('ordens.edit');

    Route::match(['put', 'patch'], '/ordens/{orden}', [OrdemServicoController::class, 'update'])
        ->middleware('role:admin')
        ->name('ordens.update');

    // Atribuir técnico/gestor (modal da lista)
    Route::post('/ordens/{orden}/atribuir', [OrdemServicoController::class, 'atribuir'])
        ->middleware('role:admin|gestor')
        ->name('ordens.atribuir');

    // Custo da OS
    Route::get('ordens/{orden}/custo', [OrdemServicoController::class, 'editCusto'])
        ->middleware('role:admin|gestor')
        ->name('ordens.custo.edit');

    Route::put('ordens/{orden}/custo', [OrdemServicoController::class, 'updateCusto'])
        ->middleware('role:admin|gestor')
        ->name('ordens.custo.update');

    // Ações de técnico: executar / concluir OS
    Route::post('ordens/{orden}/executar', [OrdemServicoController::class, 'executar'])
        ->name('ordens.executar');

    Route::post('ordens/{orden}/concluir', [OrdemServicoController::class, 'concluir'])
        ->name('ordens.concluir');

    /*
     |------------------------------------------------------------------
     | SETORES
     |------------------------------------------------------------------
     */
    Route::resource('setores', SetoresController::class)
        ->middleware('role:admin|gestor')
        ->parameters(['setores' => 'setor'])
        ->except(['show']);

    /*
     |------------------------------------------------------------------
     | EQUIPAMENTOS
     |------------------------------------------------------------------
     */
    Route::resource('equipamentos', EquipamentoController::class)
        ->middleware('role:admin|gestor|tecnico')
        ->parameters(['equipamentos' => 'equipamento']);

    // Remover arquivo de equipamento
    Route::delete(
        '/equipamentos/arquivos/{arquivo}',
        [EquipamentoController::class, 'destroyArquivo']
    )->middleware('role:admin|gestor|tecnico')
     ->name('equipamentos.arquivos.destroy');

    /*
     |------------------------------------------------------------------
     | PROJETOS
     |------------------------------------------------------------------
     */
    Route::resource('projetos', ProjetoController::class);

    /*
     |------------------------------------------------------------------
     | DASHBOARD TÉCNICO
     |------------------------------------------------------------------
     */
    Route::middleware(['role:tecnico'])->group(function () {
        Route::get('/tecnico/dashboard', [TecnicoDashboardController::class, 'index'])
            ->name('tecnico.dashboard');
    });

    /*
     |------------------------------------------------------------------
     | DASHBOARD VISUALIZADOR
     |  - Aqui ele verá os dados filtrados para ele.
     |------------------------------------------------------------------
     */
    Route::middleware(['role:visualizador'])->group(function () {
        Route::get(
            '/painel-visualizador',
            [DashboardVisualizadorController::class, 'index']
        )->name('dashboard.visualizador');
    });
    
    /*
     |------------------------------------------------------------------
     | RELATORIOS
     |------------------------------------------------------------------
     */
    Route::middleware(['auth', 'verified', 'role:admin|gestor'])->group(function () {
        Route::get('/relatorios', [RelatorioController::class, 'index'])
        ->name('relatorios.index');
    });

    /*
     |------------------------------------------------------------------
     | MANUTENÇÕES PREVENTIVAS
     |------------------------------------------------------------------
     */
    // Página com calendário + lista + modal
    Route::get(
        '/manutencoes-preventivas',
        [ManutencaoPreventivaController::class, 'index']
    )->name('manutencoes.preventivas.index');

    // Eventos para o calendário
    Route::get(
        '/manutencoes-preventivas/events',
        [ManutencaoPreventivaController::class, 'events']
    )->name('manutencoes.preventivas.events');

    // Criar (modal)
    Route::post(
        '/manutencoes-preventivas',
        [ManutencaoPreventivaController::class, 'store']
    )->middleware('role:admin|gestor')
     ->name('manutencoes.preventivas.store');

    // Ver uma manutenção específica
    Route::get(
        '/manutencoes-preventivas/{manutencaoPreventiva}',
        [ManutencaoPreventivaController::class, 'show']
    )->name('manutencoes.preventivas.show');

    // Editar
    Route::get(
        '/manutencoes-preventivas/{manutencaoPreventiva}/edit',
        [ManutencaoPreventivaController::class, 'edit']
    )->middleware('role:admin|gestor')
     ->name('manutencoes.preventivas.edit');

    // Atualizar
    Route::put(
        '/manutencoes-preventivas/{manutencaoPreventiva}',
        [ManutencaoPreventivaController::class, 'update']
    )->middleware('role:admin|gestor')
     ->name('manutencoes.preventivas.update');

    // Marcar como concluída
    Route::patch(
        '/manutencoes-preventivas/{manutencaoPreventiva}/concluir',
        [ManutencaoPreventivaController::class, 'concluir']
    )->middleware('role:admin|gestor')
     ->name('manutencoes.preventivas.concluir');

    // Excluir
    Route::delete(
        '/manutencoes-preventivas/{manutencaoPreventiva}',
        [ManutencaoPreventivaController::class, 'destroy']
    )->middleware('role:admin|gestor')
     ->name('manutencoes.preventivas.destroy');
});

/*
 |----------------------------------------------------------------------
 | DOWNLOAD / VISUALIZAÇÃO DE ARQUIVOS DE EQUIPAMENTO
 |  (mantive fora do auth, como já estava antes)
 |----------------------------------------------------------------------
 */
Route::get(
    '/equipamentos/arquivos/{arquivo}',
    [EquipamentoController::class, 'showArquivo']
)->name('equipamentos.arquivos.show');

require __DIR__.'/auth.php';
