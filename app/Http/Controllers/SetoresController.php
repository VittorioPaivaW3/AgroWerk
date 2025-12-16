<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;

class SetoresController extends Controller
{
    public function index()
    {
        $setores = Setor::orderBy('nome')->paginate(10);

        return view('setores.index', compact('setores'));
    }

    public function create()
    {
        return view('setores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        Setor::create($data);

        return redirect()
            ->route('setores.index')
            ->with('success', 'Setor cadastrado com sucesso!');
    }

    public function edit(Setor $setor)
    {
        return view('setores.edit', compact('setor'));
    }

    public function update(Request $request, Setor $setor)
    {
        $data = $request->validate([
            'nome'      => ['required', 'string', 'max:255'],
            'codigo'    => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
        ]);

        $setor->update($data);

        return redirect()
            ->route('setores.index')
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Setor $setor)
    {
        // Se seu FK em equipamentos está com cascadeOnDelete,
        // apagar o setor também apaga os equipamentos dele.
        $setor->delete();

        return redirect()
            ->route('setores.index')
            ->with('success', 'Setor excluído com sucesso!');
    }
}
