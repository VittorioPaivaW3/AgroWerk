<?php

namespace App\Http\Controllers;

use App\Models\TipoEquipamento;
use Illuminate\Http\Request;

class TipoEquipamentoController extends Controller
{
    public function index()
    {
        $tipos = TipoEquipamento::withCount('equipamentos')
            ->orderBy('nome')
            ->paginate(10);

        return view('tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        TipoEquipamento::create($data);

        return redirect()
            ->route('tipos.index')
            ->with('success', 'Tipo de equipamento cadastrado com sucesso!');
    }

    public function update(Request $request, TipoEquipamento $tipo)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $tipo->update($data);

        return redirect()
            ->route('tipos.index')
            ->with('success', 'Tipo de equipamento atualizado com sucesso!');
    }

    public function destroy(TipoEquipamento $tipo)
    {
        $tipo->delete();

        return redirect()
            ->route('tipos.index')
            ->with('success', 'Tipo de equipamento excluido com sucesso!');
    }
}
