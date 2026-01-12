<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Setor;
use App\Models\User;
use App\Models\Equipamento;
use App\Models\OrdemServicoAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdemServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user?->hasRole('visualizador')) {
            abort(403);
        }

        $filters = $request->only(['data', 'setor_id', 'tecnico_id', 'concluida']);
        $filters['concluida'] = $request->has('concluida')
            ? $request->query('concluida')
            : '0'; // default nao concluidas, mas respeita "Todas" se vier vazio

        $ordens = OrdemServico::with(['setor', 'tecnicos:id,name', 'gestores:id,name'])
            ->when(!empty($filters['data']), function ($q) use ($filters) {
                $q->whereDate('created_at', $filters['data']);
            })
            ->when(!empty($filters['setor_id']), function ($q) use ($filters) {
                $q->where('setor_id', $filters['setor_id']);
            })
            ->when(!empty($filters['tecnico_id']), function ($q) use ($filters) {
                $q->whereHas('tecnicos', function ($sub) use ($filters) {
                    $sub->where('users.id', $filters['tecnico_id']);
                });
            })
            ->when(isset($filters['concluida']) && $filters['concluida'] !== '', function ($q) use ($filters) {
                if ($filters['concluida'] === '1') {
                    $q->where('status', 'concluida');
                } elseif ($filters['concluida'] === '0') {
                    $q->where('status', '!=', 'concluida');
                }
            })
            ->latest()
            ->paginate(10);

        $tecnicos = User::role('tecnico')->orderBy('name')->get(['id', 'name']);
        $gestores = User::role('gestor')->orderBy('name')->get(['id', 'name']);
        $setores  = Setor::orderBy('nome')->get(['id', 'nome']);

        return view('ordens.index', compact('ordens', 'tecnicos', 'gestores', 'setores', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setores = Setor::orderBy('nome')->get();
        $equipamentos = Equipamento::with('setor')
            ->orderBy('nome')
            ->get();

        // Buscando por ROLE (Spatie)
        $tecnicos = User::role('tecnico')->orderBy('name')->get();
        $gestores = User::role('gestor')->orderBy('name')->get();

        return view('ordens.create', compact('setores', 'equipamentos', 'tecnicos', 'gestores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'anexos.*.uploaded' => 'Arquivo maior que 2MB. Escolha outro.',
            'anexos.*.max'      => 'Arquivo maior que 2MB. Escolha outro.',
        ];

        $data = $request->validate([
            'setor_id'       => ['required', 'exists:setores,id'],
            'equipamento_id' => ['required', 'exists:equipamentos,id'],
            'tipo'           => ['required', 'in:corretiva,preventiva'],
            'prioridade'     => ['required', 'in:baixo,medio,alto,muito_alto'],
            'descricao'      => ['required', 'string'],

            'tecnicos'   => ['nullable', 'array'],
            'tecnicos.*' => ['integer', 'exists:users,id'],
            'gestores'   => ['nullable', 'array'],
            'gestores.*' => ['integer', 'exists:users,id'],

            'anexos.*'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], $messages);

        $equipamentoValido = Equipamento::where('id', $data['equipamento_id'] ?? null)
            ->where('setor_id', $data['setor_id'] ?? null)
            ->exists();

        if (! $equipamentoValido) {
            return back()
                ->withErrors(['equipamento_id' => 'Equipamento não pertence ao setor selecionado.'])
                ->withInput();
        }

        $ordem = OrdemServico::create([
            'solicitante_id' => auth()->id(),
            'setor_id'       => $data['setor_id'],
            'equipamento_id' => $data['equipamento_id'],
            'tipo'           => $data['tipo'],
            'prioridade'     => $data['prioridade'],
            'descricao'      => $data['descricao'],
            'status'         => 'aberta',
        ]);

        // atribuir técnicos/gestores (pivot ordem_servico_user)
        if (!empty($data['tecnicos'])) {
            foreach ($data['tecnicos'] as $userId) {
                $ordem->usuarios()->attach($userId, ['papel' => 'tecnico']);
            }
        }

        if (!empty($data['gestores'])) {
            foreach ($data['gestores'] as $userId) {
                $ordem->usuarios()->attach($userId, ['papel' => 'gestor']);
            }
        }

        // Salvar anexos (se houver)
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('ordens/anexos', 'public');

                OrdemServicoAnexo::create([
                    'ordem_servico_id' => $ordem->id,
                    'path'             => $path,
                    'nome_original'    => $file->getClientOriginalName(),
                    'mime_type'        => $file->getClientMimeType(),
                    'size'             => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('ordens.show', $ordem)
            ->with('success', 'Ordem de serviço criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdemServico $orden)
    {
        $this->autorizaVisualizador($orden);

        $user = auth()->user();

        if ($user?->hasRole('tecnico')) {
            $ehTecnicoDaOrdem = $orden->tecnicos()->where('users.id', $user->id)->exists();

            if (! $ehTecnicoDaOrdem) {
                abort(403, 'Voce nao tem permissao para acessar esta OS.');
            }
        }

        $orden->load([
            'solicitante',
            'equipamento.setor',
            'setor',
            'anexos',
            'tecnicos',
            'gestores',
        ]);

        return view('ordens.show', [
            'ordem' => $orden,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdemServico $orden)
    {
        $user = auth()->user();

        $isAdmin = $user?->hasRole('admin') ?? false;
        $isVisualizador = $user?->hasRole('visualizador') ?? false;

        if (! $isAdmin && ! $isVisualizador) {
            abort(403, 'Apenas administradores ou visualizadores (autor da OS) podem editar.');
        }

        // visualizador só pode editar se for autor e OS em aberto, sem atribuições (validado no helper)
        $this->autorizaVisualizador($orden, true);

        $ordem = $orden->load([
            'solicitante',
            'equipamento.setor',
            'setor',
            'anexos',
            'tecnicos',
            'gestores',
        ]);

        $setores      = Setor::orderBy('nome')->get();
        $equipamentos = Equipamento::orderBy('nome')->get();

        // listas para os selects
        $tecnicos = User::role('tecnico')->orderBy('name')->get();
        $gestores = User::role('gestor')->orderBy('name')->get();

        return view('ordens.edit', compact(
            'ordem',
            'setores',
            'equipamentos',
            'tecnicos',
            'gestores'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrdemServico $orden)
    {
        $user = auth()->user();
        $isAdmin = $user?->hasRole('admin') ?? false;
        $isVisualizador = $user?->hasRole('visualizador') ?? false;

        if (! $isAdmin && ! $isVisualizador) {
            abort(403, 'Apenas administradores ou visualizadores (autor da OS) podem editar.');
        }

        // Visualizador (autor) s¢ pode alterar a descri‡Æo enquanto a OS est  aberta
        if ($isVisualizador && ! $isAdmin) {
            $this->autorizaVisualizador($orden, true);

            $data = $request->validate([
                'descricao' => ['required', 'string'],
            ]);

            $orden->update([
                'descricao' => $data['descricao'],
            ]);

            return redirect()
                ->route('ordens.show', $orden)
                ->with('success', 'Ordem de servi‡o atualizada com sucesso.');
        }

        $this->autorizaVisualizador($orden, true);

        $ordem = $orden;

        $messages = [
            'anexos.*.uploaded' => 'Arquivo maior que 2MB. Escolha outro.',
            'anexos.*.max'      => 'Arquivo maior que 2MB. Escolha outro.',
        ];

        $data = $request->validate([
            'descricao'      => ['nullable', 'string'],
            'status'         => ['required', 'in:aberta,em_execucao,concluida,cancelada'],
            'tipo'           => ['nullable', 'in:corretiva,preventiva'],
            'prioridade'     => ['nullable', 'in:baixo,medio,médio,alto,muito_alto'],
            'solicitante_id' => ['nullable', 'exists:users,id'],
            'setor_id'       => ['nullable', 'exists:setores,id'],
            'equipamento_id' => ['nullable', 'exists:equipamentos,id'],

            'tecnicos'   => ['nullable', 'array'],
            'tecnicos.*' => ['integer', 'exists:users,id'],
            'gestores'   => ['nullable', 'array'],
            'gestores.*' => ['integer', 'exists:users,id'],

            'atribuir_terceiros' => ['nullable', 'boolean'],

            'anexos.*'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'mimetypes:image/jpeg,image/png,application/pdf', 'max:2048'],
        ], $messages);

        $novoSetorId       = $data['setor_id']       ?? $ordem->setor_id;
        $novoEquipamentoId = $data['equipamento_id'] ?? $ordem->equipamento_id;

        if ($novoSetorId && $novoEquipamentoId) {
            $equipamentoValido = Equipamento::where('id', $novoEquipamentoId)
                ->where('setor_id', $novoSetorId)
                ->exists();

            if (! $equipamentoValido) {
                return back()
                    ->withErrors(['equipamento_id' => 'Equipamento não pertence ao setor selecionado.'])
                    ->withInput();
            }
        }

        // Atualiza campos básicos
        $ordem->update([
            'descricao'      => $data['descricao']      ?? $ordem->descricao,
            'status'         => $data['status']         ?? $ordem->status,
            'tipo'           => $data['tipo']           ?? $ordem->tipo,
            'prioridade'     => $data['prioridade']     ?? $ordem->prioridade,
            'solicitante_id' => $data['solicitante_id'] ?? $ordem->solicitante_id,
            'setor_id'       => $data['setor_id']       ?? $ordem->setor_id,
            'equipamento_id' => $data['equipamento_id'] ?? $ordem->equipamento_id,
        ]);

        // Sincronizar técnicos/gestores
        $tecnicosIds = $data['tecnicos'] ?? [];
        $gestoresIds = $data['gestores'] ?? [];

        // Limpa vínculos atuais de técnico/gestor
        $ordem->usuarios()->wherePivot('papel', 'tecnico')->detach();
        $ordem->usuarios()->wherePivot('papel', 'gestor')->detach();

        foreach ($tecnicosIds as $userId) {
            $ordem->usuarios()->attach($userId, ['papel' => 'tecnico']);
        }

        foreach ($gestoresIds as $userId) {
            $ordem->usuarios()->attach($userId, ['papel' => 'gestor']);
        }

        // Novos anexos (mantém os antigos)
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store('ordens/anexos', 'public');

                $ordem->anexos()->create([
                    'path'          => $path,
                    'nome_original' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('ordens.show', $ordem)
            ->with('success', 'Ordem de serviço atualizada com sucesso.');
    }

    /**
     * Atribui técnicos/gestores a uma OS (sem alterar demais campos).
     * Aqui entra a lógica de "atribuir a terceiros".
     */
    public function atribuir(Request $request, OrdemServico $orden)
    {
        $data = $request->validate([
            'tecnicos'          => ['nullable', 'array'],
            'tecnicos.*'        => ['integer', 'exists:users,id'],
            'gestores'          => ['nullable', 'array'],
            'gestores.*'        => ['integer', 'exists:users,id'],
            'atribuir_terceiros'=> ['nullable', 'boolean'],
        ]);

        $atribuirTerceiros = (bool)($data['atribuir_terceiros'] ?? false);

        // Se marcar "atribuir a terceiros", só pode se o equipamento for de terceiros
        if ($atribuirTerceiros) {
            $equipamento = $orden->equipamento; // relação equipamento

            if (!$equipamento) {
                return back()->with('error', 'Esta OS não possui equipamento vinculado.');
            }

            if (!$equipamento->terceiro) {
                return back()->with('error', 'Este equipamento não está marcado como de terceiros.');
            }

            // Marca OS como concluída (por terceiros)
            $orden->status = 'concluida';
            $orden->concluida_por_terceiros = true;

            $orden->save();
        }

        // Atualiza atribuições normalmente
        $orden->usuarios()->wherePivot('papel', 'tecnico')->detach();
        $orden->usuarios()->wherePivot('papel', 'gestor')->detach();

        foreach ($data['tecnicos'] ?? [] as $userId) {
            $orden->usuarios()->attach($userId, ['papel' => 'tecnico']);
        }
        foreach ($data['gestores'] ?? [] as $userId) {
            $orden->usuarios()->attach($userId, ['papel' => 'gestor']);
        }

        return redirect()
            ->route('ordens.index')
            ->with(
                'success',
                $atribuirTerceiros
                    ? 'OS atribuída a terceiros e marcada como concluída.'
                    : 'Atribuições atualizadas.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Técnico inicia a execução da OS.
     */
    public function executar(OrdemServico $orden)
    {
        $ordem = $orden;
        $user  = Auth::user();

        // Garante que o usuário é um técnico atribuído
        $ehTecnicoDaOrdem = $ordem->tecnicos()->where('users.id', $user->id)->exists();

        if (!$ehTecnicoDaOrdem) {
            abort(403, 'Você não tem permissão para executar esta OS.');
        }

        if ($ordem->status !== 'aberta') {
            return back()->with('error', 'Só é possível iniciar ordens com status ABERTA.');
        }

        $ordem->status = 'em_execucao';
        $ordem->inicio_execucao_em = now();
        $ordem->save();

        return back()->with('success', 'Ordem colocada em execução.');
    }

    /**
     * Técnico conclui a OS.
     */
    public function concluir(OrdemServico $orden)
    {
        $ordem = $orden;
        $user  = Auth::user();

        // Garante que o usuário é um técnico atribuído
        $ehTecnicoDaOrdem = $ordem->tecnicos()->where('users.id', $user->id)->exists();

        if (!$ehTecnicoDaOrdem) {
            abort(403, 'Você não tem permissão para concluir esta OS.');
        }

        if ($ordem->status !== 'em_execucao') {
            return back()->with('error', 'Só é possível concluir ordens EM EXECUÇÃO.');
        }

        $ordem->status = 'concluida';
        $ordem->fim_execucao_em = now();
        $ordem->save();

        return back()->with('success', 'Ordem concluída com sucesso.');
    }

    protected function autorizaVisualizador(OrdemServico $ordem, bool $paraEditar = false): void
    {
        $user = auth()->user();

        if (! $user || ! $user->hasRole('visualizador')) {
            return;
        }

        if ($ordem->solicitante_id !== $user->id) {
            abort(403, 'Visualizador só acessa ordens que abriu.');
        }

        if ($paraEditar) {
            $temAtribuicoes = $ordem->tecnicos()->exists() || $ordem->gestores()->exists();

            if ($ordem->status !== 'aberta' || $temAtribuicoes) {
                abort(403, 'Esta OS não pode ser editada.');
            }
        }
    }

    protected function autorizaCusto(OrdemServico $ordem): void
    {
        $user = auth()->user();

        $isAdminOuGestor =
            ($user->hasRole('admin') ?? false) ||
            ($user->hasRole('gestor') ?? false);

        if (!$isAdminOuGestor) {
            abort(403, 'Você não tem permissão para atribuir custo a esta OS.');
        }

        if ($ordem->status !== 'concluida') {
            abort(403, 'Só é possível atribuir custo a ordens concluídas.');
        }
    }

    public function editCusto(OrdemServico $orden)
    {
        $ordem = $orden;

        $this->autorizaCusto($ordem);

        return view('ordens.custo', [
            'ordem' => $ordem,
        ]);
    }

    public function updateCusto(Request $request, OrdemServico $orden)
    {
        $ordem = $orden;

        $this->autorizaCusto($ordem);

        $data = $request->validate([
            'custo_total' => ['required', 'numeric', 'min:0'],
        ]);

        $ordem->update([
            'custo_total' => $data['custo_total'],
        ]);

        return redirect()
            ->route('ordens.show', $ordem)
            ->with('success', 'Custo da OS atualizado com sucesso.');
    }

    protected function exigeAdmin(): void
    {
        $user = auth()->user();

        if (! $user?->hasRole('admin')) {
            abort(403, 'Apenas administradores podem editar ordens.');
        }
    }
}
