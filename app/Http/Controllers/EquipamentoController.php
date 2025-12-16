<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Setor;
use Illuminate\Http\Request;

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

    'extra_keys'   => ['array'],
    'extra_keys.*' => ['nullable', 'string', 'max:255'],
    'extra_values'   => ['array'],
    'extra_values.*' => ['nullable', 'string'],
]);

        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            if ($key === '' && ($value === null || $value === '')) continue;
            if ($key !== '') $extras[$key] = $value;
        }

        Equipamento::create([
            'nome'                 => $data['nome'],
            'codigo'               => $data['codigo'] ?? null,
            'cor'                  => $data['cor'] ?? null,
            'setor_id'             => $data['setor_id'],
            'status'               => $data['status'] ?? 'ativo',
            'manutencao_preventiva'=> $data['manutencao_preventiva'] ?? null,
            'observacoes'          => $data['observacoes'] ?? null,
            'campos_extras'        => ! empty($extras) ? $extras : null,
        ]);

        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function show(Equipamento $equipamento)
    {
        $equipamento->load('setor');

        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        $setores = Setor::orderBy('nome')->get();

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

    'extra_keys'   => ['array'],
    'extra_keys.*' => ['nullable', 'string', 'max:255'],
    'extra_values'   => ['array'],
    'extra_values.*' => ['nullable', 'string'],
]);

        $extras = [];
        $keys   = $request->input('extra_keys', []);
        $values = $request->input('extra_values', []);

        foreach ($keys as $index => $key) {
            $key   = trim($key ?? '');
            $value = $values[$index] ?? null;

            if ($key === '' && ($value === null || $value === '')) continue;
            if ($key !== '') $extras[$key] = $value;
        }

        $equipamento->update([
            'nome'                 => $data['nome'],
            'codigo'               => $data['codigo'] ?? null,
            'cor'                  => $data['cor'] ?? null,
            'setor_id'             => $data['setor_id'],
            'status'               => $data['status'] ?? 'ativo',
            'manutencao_preventiva'=> $data['manutencao_preventiva'] ?? null,
            'observacoes'          => $data['observacoes'] ?? null,
            'campos_extras'        => ! empty($extras) ? $extras : null,
        ]);


        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Equipamento atualizado com sucesso!');
    }
}
