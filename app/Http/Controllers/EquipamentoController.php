<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Setor;
use App\Models\TipoEquipamento;
use App\Models\EquipamentoArquivo;
use App\Models\ManutencaoAlerta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipamento::with(['setor', 'tipoEquipamento']);

        if ($request->filled('setor_id')) {
            $query->where('setor_id', $request->setor_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $equipamentos = $query
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        $setores = Setor::orderBy('nome')->get();

        return view('equipamentos.index', compact('equipamentos', 'setores'));
    }

    public function create()
    {
        $setores = Setor::orderBy('nome')->get();
        $tipos = TipoEquipamento::orderBy('nome')->get();

        return view('equipamentos.create', compact('setores', 'tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'cor'       => ['nullable', 'string', 'max:7'],
            'setor_id'  => ['required', 'exists:setores,id'],
            'tipo_equipamento_id' => ['nullable', 'exists:tipos_equipamento,id'],

            'status'    => ['required', 'in:ativo,inativo,manutencao'],
            'vida_util_h' => ['nullable', 'integer', 'min:0'],
            'horimetro'   => ['nullable', 'numeric', 'min:0'],
            'tem_horimetro' => ['nullable', 'boolean'],

            'manutencao_preventiva' => ['nullable', 'date'],
            'observacoes'           => ['nullable', 'string'],
            'terceiro'  => ['nullable', 'boolean'],

            // múltiplos anexos
            'anexos.*'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'mimetypes:image/jpeg,image/png,application/pdf', 'max:20480'],
            'foto_perfil'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'extra_keys'     => ['nullable', 'array'],
            'extra_keys.*'   => ['nullable', 'string', 'max:255'],
            'extra_values'   => ['nullable', 'array'],
            'extra_values.*' => ['nullable', 'string'],
        ]);

        $data['terceiro'] = $request->boolean('terceiro');
        $data['tem_horimetro'] = $request->boolean('tem_horimetro');

        if (! $data['tem_horimetro']) {
            $data['vida_util_h'] = null;
            $data['horimetro'] = null;
        }

        // monta campos extras
        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            // ignora linha totalmente vazia
            if ($key === '' && ($value === null || $value === '')) continue;

            // guarda como lista para permitir chaves repetidas
            $extras[] = [
                'campo' => $key,
                'valor' => $value,
            ];
        }

        $fotoPerfilPath = null;
        if ($request->hasFile('foto_perfil')) {
            $fotoPerfilPath = $request->file('foto_perfil')->store('equipamentos/perfil', 'public');
        }

        // cria equipamento
        $equipamento = Equipamento::create([
            'nome'                  => $data['nome'],
            'codigo'                => $data['codigo'] ?? null,
            'cor'                   => $data['cor'] ?? null,
            'setor_id'              => $data['setor_id'],
            'tipo_equipamento_id'   => $data['tipo_equipamento_id'] ?? null,
            'status'                => $data['status'] ?? 'ativo',
            'vida_util_h'           => $data['vida_util_h'] ?? null,
            'horimetro'             => $data['horimetro'] ?? null,
            'tem_horimetro'         => $data['tem_horimetro'] ?? false,
            'foto_perfil'           => $fotoPerfilPath,
            'manutencao_preventiva' => $data['manutencao_preventiva'] ?? null,
            'observacoes'           => $data['observacoes'] ?? null,
            'campos_extras'         => ! empty($extras) ? $extras : null,
            'terceiro'              => $data['terceiro'] ?? false,
        ]);

        // salva anexos (se houver)
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                if (! $file) continue;

                $path = $file->store('equipamentos', 'public');

                EquipamentoArquivo::create([
                    'equipamento_id' => $equipamento->id,
                    'path'           => $path,
                    'nome_original'  => $file->getClientOriginalName(),
                    'mime_type'      => $file->getClientMimeType(),
                    'size'           => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function show(Equipamento $equipamento)
    {
        $equipamento->load(['setor', 'tipoEquipamento', 'arquivos']);

        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        $setores = Setor::orderBy('nome')->get();
        $tipos = TipoEquipamento::orderBy('nome')->get();
        $equipamento->load('arquivos');

        return view('equipamentos.edit', compact('equipamento', 'setores', 'tipos'));
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'cor'       => ['nullable', 'string', 'max:7'],
            'setor_id'  => ['required', 'exists:setores,id'],
            'tipo_equipamento_id' => ['nullable', 'exists:tipos_equipamento,id'],

            'status'    => ['required', 'in:ativo,inativo,manutencao'],
            'vida_util_h' => ['nullable', 'integer', 'min:0'],
            'horimetro'   => ['nullable', 'numeric', 'min:0'],
            'tem_horimetro' => ['nullable', 'boolean'],

            'manutencao_preventiva' => ['nullable', 'date'],
            'observacoes'           => ['nullable', 'string'],
            'terceiro'  => ['nullable', 'boolean'],

            // anexos novos (opcionais)
            'anexos.*'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'mimetypes:image/jpeg,image/png,application/pdf', 'max:20480'],
            'foto_perfil'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remover_foto_perfil'   => ['nullable', 'boolean'],

            'extra_keys'     => ['nullable', 'array'],
            'extra_keys.*'   => ['nullable', 'string', 'max:255'],
            'extra_values'   => ['nullable', 'array'],
            'extra_values.*' => ['nullable', 'string'],
        ]);
        $data['terceiro'] = $request->boolean('terceiro');
        $data['tem_horimetro'] = $request->boolean('tem_horimetro');

        if (! $data['tem_horimetro']) {
            $data['vida_util_h'] = null;
            $data['horimetro'] = null;
        }

        // monta campos extras
        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            if ($key === '' && ($value === null || $value === '')) continue;

            $extras[] = [
                'campo' => $key,
                'valor' => $value,
            ];
        }

        $fotoPerfilPath = $equipamento->foto_perfil;
        if ($request->hasFile('foto_perfil')) {
            if ($fotoPerfilPath && Storage::disk('public')->exists($fotoPerfilPath)) {
                Storage::disk('public')->delete($fotoPerfilPath);
            }
            $fotoPerfilPath = $request->file('foto_perfil')->store('equipamentos/perfil', 'public');
        } elseif ($request->boolean('remover_foto_perfil')) {
            if ($fotoPerfilPath && Storage::disk('public')->exists($fotoPerfilPath)) {
                Storage::disk('public')->delete($fotoPerfilPath);
            }
            $fotoPerfilPath = null;
        }

        // atualiza equipamento
        $equipamento->update([
            'nome'                  => $data['nome'],
            'codigo'                => $data['codigo'] ?? null,
            'cor'                   => $data['cor'] ?? null,
            'setor_id'              => $data['setor_id'],
            'tipo_equipamento_id'   => $data['tipo_equipamento_id'] ?? null,
            'status'                => $data['status'] ?? 'ativo',
            'vida_util_h'           => $data['vida_util_h'] ?? null,
            'horimetro'             => $data['horimetro'] ?? null,
            'tem_horimetro'         => $data['tem_horimetro'] ?? false,
            'foto_perfil'           => $fotoPerfilPath,
            'manutencao_preventiva' => $data['manutencao_preventiva'] ?? null,
            'observacoes'           => $data['observacoes'] ?? null,
            'campos_extras'         => ! empty($extras) ? $extras : null,
            'terceiro'              => $data['terceiro'] ?? false,
        ]);

        // adiciona novos anexos sem apagar os antigos
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                if (! $file) continue;

                $path = $file->store('equipamentos', 'public');

                EquipamentoArquivo::create([
                    'equipamento_id' => $equipamento->id,
                    'path'           => $path,
                    'nome_original'  => $file->getClientOriginalName(),
                    'mime_type'      => $file->getClientMimeType(),
                    'size'           => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Equipamento atualizado com sucesso!');
    }

    /**
     * Remove um arquivo específico do equipamento.
     */
    public function destroyArquivo(EquipamentoArquivo $arquivo)
    {
        // apaga arquivo físico
        if ($arquivo->path) {
            Storage::disk('public')->delete($arquivo->path);
        }

        $equipamentoId = $arquivo->equipamento_id;

        $arquivo->delete();

        return redirect()
            ->route('equipamentos.edit', $equipamentoId)
            ->with('success', 'Anexo removido com sucesso!');
    }
    
    /**
     * Exibe/fornece download de um arquivo do equipamento.
     */
    public function showArquivo(EquipamentoArquivo $arquivo)
    {
        if (! $arquivo->path || ! Storage::disk('public')->exists($arquivo->path)) {
            abort(404);
        }

        $filename = $arquivo->nome_original ?? basename($arquivo->path);

        return Storage::disk('public')->response($arquivo->path, $filename);
    }

    /**
     * Lista equipamentos com vida útil e horímetro para lançamento.
     */
    public function horimetros(Request $request)
    {
        $query = Equipamento::with(['alertas' => function ($q) {
                $q->orderByDesc('created_at');
            }, 'setor'])
            ->where('tem_horimetro', true)
            ->select('id', 'nome', 'codigo', 'vida_util_h', 'horimetro', 'setor_id');

        if ($request->filled('setor_id')) {
            $query->where('setor_id', $request->setor_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $equipamentos = $query
            ->orderBy('nome')
            ->get();

        $setores = Setor::orderBy('nome')->get();

        return view('equipamentos.horimetros', compact('equipamentos', 'setores'));
    }

    public function storeHorimetro(Request $request, Equipamento $equipamento)
    {
        $data = $request->validate([
            'horimetro' => ['required', 'numeric', 'min:0'],
        ]);

        $equipamento->horimetro = ($equipamento->horimetro ?? 0) + $data['horimetro'];
        $equipamento->save();

        if ($request->expectsJson()) {
            $equipamento->load(['alertas', 'setor']);

            return response()->json([
                'message' => 'Horimetro lancado com sucesso!',
                'data' => [
                    'equipamento_id' => $equipamento->id,
                    'horimetro' => (float) $equipamento->horimetro,
                    'vida_util_h' => $equipamento->vida_util_h !== null ? (int) $equipamento->vida_util_h : null,
                    'alertas_horimetro' => $this->alertasHorimetroPayload($equipamento),
                ],
            ]);
        }


        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Horímetro lançado com sucesso!');
    }

    public function zerarHorimetro(Equipamento $equipamento)
    {
        $equipamento->horimetro = 0;
        $equipamento->save();

        $equipamento->alertas()
            ->where('tipo', 'horimetro')
            ->whereNotNull('horimetro_intervalo')
            ->get()
            ->each(function (ManutencaoAlerta $alerta) {
                $alerta->update([
                    'horimetro_base' => 0,
                    'horimetro_alvo' => (float) $alerta->horimetro_intervalo,
                    'last_sent_at' => null,
                ]);
            });

        if (request()->expectsJson()) {
            $equipamento->load(['alertas', 'setor']);

            return response()->json([
                'message' => 'Horimetro zerado apos manutencao.',
                'data' => [
                    'equipamento_id' => $equipamento->id,
                    'horimetro' => 0,
                    'vida_util_h' => $equipamento->vida_util_h !== null ? (int) $equipamento->vida_util_h : null,
                    'alertas_horimetro' => $this->alertasHorimetroPayload($equipamento),
                ],
            ]);
        }


        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Horímetro zerado após manutenção.');
    }

    public function storeAlerta(Request $request)
    {
        $data = $request->validate([
            'equipamento_id'    => ['required', 'exists:equipamentos,id'],
            'nome'              => ['nullable', 'string', 'max:255'],
            'mensagem'          => ['nullable', 'string', 'max:2000'],
            'tipo'              => ['required', 'in:data,horimetro'],
            'recorrente'        => ['nullable', 'boolean'],
            'dias_recorrencia'  => ['nullable', 'integer', 'min:1'],
            'data_inicio_recorrencia' => ['nullable', 'date'],
            'data_alerta'       => ['nullable', 'date'],
            'horimetro_alvo'    => ['nullable', 'numeric', 'min:0'],
            'horimetro_intervalo' => ['nullable', 'numeric', 'min:0.01'],
            'horimetro_base'    => ['nullable', 'numeric', 'min:0'],
            'horimetro_antecedencia' => ['nullable', 'numeric', 'min:0'],
            'horimetro_items' => ['nullable', 'array'],
            'horimetro_items.*.nome' => ['nullable', 'string', 'max:255'],
            'horimetro_items.*.mensagem' => ['nullable', 'string', 'max:2000'],
            'horimetro_items.*.horimetro_intervalo' => ['nullable', 'numeric', 'min:0.01'],
            'horimetro_items.*.horimetro_base' => ['nullable', 'numeric', 'min:0'],
            'horimetro_items.*.horimetro_antecedencia' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['recorrente'] = $request->boolean('recorrente');
        $data['ativo'] = true;
        unset($data['horimetro_items']);

        if ($data['tipo'] === 'data') {
            if ($data['recorrente']) {
                $request->validate([
                    'dias_recorrencia' => ['required', 'integer', 'min:1'],
                    'data_inicio_recorrencia' => ['required', 'date'],
                ]);
                $data['data_alerta'] = null; // não usada em recorrente
            } else {
                $request->validate([
                    'data_alerta' => ['required', 'date'],
                ]);
                $data['dias_recorrencia'] = null;
                $data['data_inicio_recorrencia'] = null;
            }
            $data['horimetro_alvo'] = null;
            $data['horimetro_intervalo'] = null;
            $data['horimetro_base'] = null;
            $data['horimetro_antecedencia'] = 10;
        }

        if ($data['tipo'] === 'horimetro') {
            $itensHorimetro = collect($request->input('horimetro_items', []))
                ->filter(function ($item) {
                    return filled($item['nome'] ?? null)
                        || filled($item['mensagem'] ?? null)
                        || filled($item['horimetro_intervalo'] ?? null)
                        || filled($item['horimetro_base'] ?? null)
                        || filled($item['horimetro_antecedencia'] ?? null);
                })
                ->values();

            if ($itensHorimetro->isNotEmpty()) {
                $request->validate([
                    'horimetro_items' => ['required', 'array', 'min:1'],
                    'horimetro_items.*.horimetro_intervalo' => ['required', 'numeric', 'min:0.01'],
                ]);

                $equipamento = Equipamento::findOrFail($data['equipamento_id']);

                $itensHorimetro->each(function (array $item) use ($data, $equipamento) {
                    $base = filled($item['horimetro_base'] ?? null)
                        ? (float) $item['horimetro_base']
                        : (float) ($equipamento->horimetro ?? 0);
                    $intervalo = (float) $item['horimetro_intervalo'];
                    $antecedencia = filled($item['horimetro_antecedencia'] ?? null)
                        ? (float) $item['horimetro_antecedencia']
                        : 10;

                    ManutencaoAlerta::create([
                        'equipamento_id' => $data['equipamento_id'],
                        'nome' => $item['nome'] ?? null,
                        'mensagem' => $item['mensagem'] ?? null,
                        'tipo' => 'horimetro',
                        'recorrente' => false,
                        'dias_recorrencia' => null,
                        'data_inicio_recorrencia' => null,
                        'data_alerta' => null,
                        'horimetro_base' => $base,
                        'horimetro_intervalo' => $intervalo,
                        'horimetro_antecedencia' => $antecedencia,
                        'horimetro_alvo' => $base + $intervalo,
                        'ativo' => true,
                    ]);
                });

                return redirect()
                    ->route('equipamentos.horimetros')
                    ->with('success', $itensHorimetro->count() . ' manutenção(ões) por horímetro criada(s) com sucesso!');
            }

            $request->validate([
                'horimetro_intervalo' => ['required', 'numeric', 'min:0.01'],
            ]);

            $equipamento = Equipamento::findOrFail($data['equipamento_id']);
            $data['horimetro_base'] = array_key_exists('horimetro_base', $data) && $data['horimetro_base'] !== null
                ? (float) $data['horimetro_base']
                : (float) ($equipamento->horimetro ?? 0);
            $data['horimetro_intervalo'] = (float) $data['horimetro_intervalo'];
            $data['horimetro_antecedencia'] = array_key_exists('horimetro_antecedencia', $data) && $data['horimetro_antecedencia'] !== null
                ? (float) $data['horimetro_antecedencia']
                : 10;
            $data['horimetro_alvo'] = $data['horimetro_base'] + $data['horimetro_intervalo'];
            $data['recorrente'] = false;
            $data['dias_recorrencia'] = null;
            $data['data_inicio_recorrencia'] = null;
            $data['data_alerta'] = null;
        }

        ManutencaoAlerta::create($data);

        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Alerta de manutenção criado com sucesso!');
    }

    public function updateAlerta(Request $request, ManutencaoAlerta $alerta)
    {
        $data = $request->validate([
            'equipamento_id'    => ['required', 'exists:equipamentos,id'],
            'nome'              => ['nullable', 'string', 'max:255'],
            'mensagem'          => ['nullable', 'string', 'max:2000'],
            'tipo'              => ['required', 'in:data,horimetro'],
            'recorrente'        => ['nullable', 'boolean'],
            'dias_recorrencia'  => ['nullable', 'integer', 'min:1'],
            'data_inicio_recorrencia' => ['nullable', 'date'],
            'data_alerta'       => ['nullable', 'date'],
            'horimetro_alvo'    => ['nullable', 'numeric', 'min:0'],
            'horimetro_intervalo' => ['nullable', 'numeric', 'min:0.01'],
            'horimetro_base'    => ['nullable', 'numeric', 'min:0'],
            'horimetro_antecedencia' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['recorrente'] = $request->boolean('recorrente');

        if ($data['tipo'] === 'data') {
            if ($data['recorrente']) {
                $request->validate([
                    'dias_recorrencia' => ['required', 'integer', 'min:1'],
                    'data_inicio_recorrencia' => ['required', 'date'],
                ]);
                $data['data_alerta'] = null;
            } else {
                $request->validate([
                    'data_alerta' => ['required', 'date'],
                ]);
                $data['dias_recorrencia'] = null;
                $data['data_inicio_recorrencia'] = null;
            }
            $data['horimetro_alvo'] = null;
            $data['horimetro_intervalo'] = null;
            $data['horimetro_base'] = null;
            $data['horimetro_antecedencia'] = 10;
        }

        if ($data['tipo'] === 'horimetro') {
            $request->validate([
                'horimetro_intervalo' => ['required', 'numeric', 'min:0.01'],
            ]);

            $equipamento = Equipamento::findOrFail($data['equipamento_id']);
            $data['horimetro_base'] = array_key_exists('horimetro_base', $data) && $data['horimetro_base'] !== null
                ? (float) $data['horimetro_base']
                : (float) ($equipamento->horimetro ?? 0);
            $data['horimetro_intervalo'] = (float) $data['horimetro_intervalo'];
            $data['horimetro_antecedencia'] = array_key_exists('horimetro_antecedencia', $data) && $data['horimetro_antecedencia'] !== null
                ? (float) $data['horimetro_antecedencia']
                : 10;
            $data['horimetro_alvo'] = $data['horimetro_base'] + $data['horimetro_intervalo'];
            $data['recorrente'] = false;
            $data['dias_recorrencia'] = null;
            $data['data_inicio_recorrencia'] = null;
            $data['data_alerta'] = null;
        }

        $alerta->update($data);

        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Alerta atualizado com sucesso!');
    }

    public function realizarAlertaHorimetro(Request $request, ManutencaoAlerta $alerta)
    {
        abort_if($alerta->tipo !== 'horimetro', 422, 'Este aviso não é por horímetro.');

        $alerta->load('equipamento.setor');
        $equipamento = $alerta->equipamento;
        abort_if(! $equipamento, 404, 'Equipamento não encontrado.');

        $horimetroAtual = (float) ($equipamento->horimetro ?? 0);
        $intervalo = $alerta->horimetro_intervalo !== null ? (float) $alerta->horimetro_intervalo : null;

        $alerta->update([
            'horimetro_base' => $horimetroAtual,
            'horimetro_alvo' => $intervalo ? $horimetroAtual + $intervalo : $alerta->horimetro_alvo,
            'ultimo_realizado_em' => now(),
            'ultimo_realizado_horimetro' => $horimetroAtual,
            'last_sent_at' => null,
        ]);

        $equipamento->load(['alertas', 'setor']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Manutenção marcada como feita.',
                'data' => [
                    'equipamento_id' => $equipamento->id,
                    'alertas_horimetro' => $this->alertasHorimetroPayload($equipamento),
                ],
            ]);
        }

        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Manutenção marcada como feita.');
    }

    private function alertasHorimetroPayload(Equipamento $equipamento): array
    {
        $horimetroAtual = (float) ($equipamento->horimetro ?? 0);

        return $equipamento->alertas
            ->where('tipo', 'horimetro')
            ->where('ativo', true)
            ->map(function (ManutencaoAlerta $alerta) use ($equipamento, $horimetroAtual) {
                $alerta->setRelation('equipamento', $equipamento);
                $resumo = $alerta->resumoHorimetro($horimetroAtual);
                $resumo['realizar_url'] = route('equipamentos.alertas.realizar-horimetro', $alerta);

                return $resumo;
            })
            ->sortBy(function (array $alerta) {
                return $alerta['horas_restantes'] ?? PHP_FLOAT_MAX;
            })
            ->values()
            ->all();
    }

    /**
     * Envia um alerta de teste para os gestores/admins usando o primeiro equipamento disponível.
     */
    public function testeAlerta()
    {
        $equipamento = Equipamento::first();
        if (! $equipamento) {
            return redirect()
                ->route('equipamentos.horimetros')
                ->with('error', 'Nenhum equipamento cadastrado para enviar alerta de teste.');
        }

        $alerta = ManutencaoAlerta::create([
            'equipamento_id'   => $equipamento->id,
            'mensagem'         => 'Alerta de teste - ignore.',
            'tipo'             => 'data',
            'recorrente'       => false,
            'data_alerta'      => now()->addDay()->toDateString(),
            'dias_recorrencia' => null,
            'horimetro_alvo'   => null,
        ]);

        return redirect()
            ->route('equipamentos.horimetros')
            ->with('success', 'Alerta de teste enviado para administradores/gestores.');
    }
}
