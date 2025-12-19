<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Setor;
use App\Models\EquipamentoArquivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipamento::with('setor');

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

        return view('equipamentos.create', compact('setores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'cor'       => ['nullable', 'string', 'max:7'],
            'setor_id'  => ['required', 'exists:setores,id'],

            'status'    => ['required', 'in:ativo,inativo,manutencao'],

            'manutencao_preventiva' => ['nullable', 'date'],
            'observacoes'           => ['nullable', 'string'],

            // múltiplos anexos
            'anexos.*'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],

            'extra_keys'     => ['nullable', 'array'],
            'extra_keys.*'   => ['nullable', 'string', 'max:255'],
            'extra_values'   => ['nullable', 'array'],
            'extra_values.*' => ['nullable', 'string'],
        ]);

        // monta campos extras
        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            if ($key === '' && ($value === null || $value === '')) continue;
            if ($key !== '') $extras[$key] = $value;
        }

        // cria equipamento
        $equipamento = Equipamento::create([
            'nome'                  => $data['nome'],
            'codigo'                => $data['codigo'] ?? null,
            'cor'                   => $data['cor'] ?? null,
            'setor_id'              => $data['setor_id'],
            'status'                => $data['status'] ?? 'ativo',
            'manutencao_preventiva' => $data['manutencao_preventiva'] ?? null,
            'observacoes'           => $data['observacoes'] ?? null,
            'campos_extras'         => ! empty($extras) ? $extras : null,
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
        $equipamento->load(['setor', 'arquivos']);

        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        $setores = Setor::orderBy('nome')->get();
        $equipamento->load('arquivos');

        return view('equipamentos.edit', compact('equipamento', 'setores'));
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'cor'       => ['nullable', 'string', 'max:7'],
            'setor_id'  => ['required', 'exists:setores,id'],

            'status'    => ['required', 'in:ativo,inativo,manutencao'],

            'manutencao_preventiva' => ['nullable', 'date'],
            'observacoes'           => ['nullable', 'string'],

            // anexos novos (opcionais)
            'anexos.*'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],

            'extra_keys'     => ['nullable', 'array'],
            'extra_keys.*'   => ['nullable', 'string', 'max:255'],
            'extra_values'   => ['nullable', 'array'],
            'extra_values.*' => ['nullable', 'string'],
        ]);

        // monta campos extras
        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            if ($key === '' && ($value === null || $value === '')) continue;
            if ($key !== '') $extras[$key] = $value;
        }

        // atualiza equipamento
        $equipamento->update([
            'nome'                  => $data['nome'],
            'codigo'                => $data['codigo'] ?? null,
            'cor'                   => $data['cor'] ?? null,
            'setor_id'              => $data['setor_id'],
            'status'                => $data['status'] ?? 'ativo',
            'manutencao_preventiva' => $data['manutencao_preventiva'] ?? null,
            'observacoes'           => $data['observacoes'] ?? null,
            'campos_extras'         => ! empty($extras) ? $extras : null,
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
}
