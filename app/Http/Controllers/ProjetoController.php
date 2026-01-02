<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\ProjetoOrcamento;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::with('setor')
            ->orderBy('prazo')
            ->paginate(10);

        return view('projetos.index', compact('projetos'));
    }

    public function create()
    {
        $setores = Setor::orderBy('nome')->get();

        return view('projetos.create', compact('setores'));
    }

    public function store(Request $request)
    {
        $messages = [
            'orcamentos.*.uploaded' => 'Arquivo maior que o permitido.',
            'orcamentos.*.max'      => 'Arquivo maior que o permitido.',
        ];

        $data = $request->validate([
            'setores_id'          => ['required', 'exists:setores,id'],
            'titulo'            => ['required', 'string', 'max:255'],
            'descricao'         => ['nullable', 'string'],
            'prazo'             => ['nullable', 'date'],
            'orcamento_previsto'=> ['nullable', 'numeric', 'min:0'],
            'orcamento_real'    => ['nullable', 'numeric', 'min:0'],
            'status'            => ['required', 'in:aberto,em_andamento,concluido,cancelado'],
                   
            'orcamentos.*' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:4096',
            ],
        ], $messages);

        $projeto = Projeto::create([
            'setores_id'  => $data['setores_id'],
            'titulo'    => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'prazo'     => $data['prazo'] ?? null,
            'orcamento_previsto' => $data['orcamento_previsto'] ?? null,
            'orcamento_real'     => $data['orcamento_real'] ?? null,
            'status'    => $data['status'] ?? 'aberto',
        ]);

        if ($request->hasFile('orcamentos')) {
            foreach ($request->file('orcamentos') as $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('projetos/orcamentos', 'public');

                $projeto->orcamentos()->create([
                    'path'          => $path,
                    'nome_original' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Projeto $projeto)
    {
        $projeto->load(['setor', 'orcamentos']);

        return view('projetos.show', compact('projeto'));
    }

    public function edit(Projeto $projeto)
    {
        $setores = Setor::orderBy('nome')->get();
        $projeto->load('orcamentos');

        return view('projetos.edit', compact('projeto', 'setores'));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $messages = [
            'orcamentos.*.uploaded' => 'Arquivo maior que o permitido.',
            'orcamentos.*.max'      => 'Arquivo maior que o permitido.',
        ];

        $data = $request->validate([
            'setores_id'          => ['required', 'exists:setores,id'],
            'titulo'            => ['required', 'string', 'max:255'],
            'descricao'         => ['nullable', 'string'],
            'prazo'             => ['nullable', 'date'],
            'orcamento_previsto'=> ['nullable', 'numeric', 'min:0'],
            'orcamento_real'    => ['nullable', 'numeric', 'min:0'],
            'status'            => ['required', 'in:aberto,em_andamento,concluido,cancelado'],
                    
            'orcamentos.*' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:4096',
            ],
        ], $messages);

        $projeto->update($data);

        if ($request->hasFile('orcamentos')) {
            foreach ($request->file('orcamentos') as $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('projetos/orcamentos', 'public');

                $projeto->orcamentos()->create([
                    'path'          => $path,
                    'nome_original' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('projetos.show', $projeto)
            ->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Projeto $projeto)
    {
        // se quiser deletar arquivos físicos também:
        foreach ($projeto->orcamentos as $orcamento) {
            if ($orcamento->path) {
                Storage::disk('public')->delete($orcamento->path);
            }
        }

        $projeto->delete();

        return redirect()
            ->route('projetos.index')
            ->with('success', 'Projeto removido com sucesso!');
    }
}
