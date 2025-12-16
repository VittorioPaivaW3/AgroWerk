<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\ManutencaoPreventiva;
use Illuminate\Http\Request;
use App\Models\Setor;
use Illuminate\Support\Carbon;

class ManutencaoPreventivaController extends Controller
{
    public function index(Request $request)
    {
        // combos dos filtros
        $equipamentos = Equipamento::with('setor')
            ->orderBy('nome')
            ->get();

        $setores = Setor::orderBy('nome')->get();

        // base da query
        $query = ManutencaoPreventiva::with(['equipamento.setor'])
            ->orderBy('data_prevista', 'asc');

        // filtro por equipamento
        if ($request->filled('equipamento_id')) {
            $query->where('equipamento_id', $request->equipamento_id);
        }

        // filtro por setor (via relacionamento com equipamento)
        if ($request->filled('setor_id')) {
            $query->whereHas('equipamento', function ($q) use ($request) {
                $q->where('setor_id', $request->setor_id);
            });
        }

        // filtro por data exata
        if ($request->filled('data')) {
            $query->whereDate('data_prevista', $request->data);
        }

        $manutencoes = $query->get();

        return view('manutencoes.preventivas.index', compact(
            'manutencoes',
            'equipamentos',
            'setores'
        ));
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        $startDate = $start ? Carbon::parse($start)->startOfDay() : null;
        $endDate   = $end   ? Carbon::parse($end)->endOfDay()    : null;

        $query = ManutencaoPreventiva::with('equipamento');

        if ($startDate) {
            $query->whereDate('data_prevista', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('data_prevista', '<=', $endDate);
        }

        $manutencoes = $query->get();

        $events = $manutencoes->map(function ($manutencao) {
            $equip = $manutencao->equipamento;

            return [
                'id'    => $manutencao->id,
                'title' => ($equip?->nome ?? 'Equipamento') . ' - ' . $manutencao->descricao,
                'start' => $manutencao->data_prevista?->format('Y-m-d'),
                'url'   => $equip ? route('equipamentos.show', $equip) : null,
                'backgroundColor' => $equip?->cor ?: '#2563eb',
                'borderColor'     => $equip?->cor ?: '#1d4ed8',
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'equipamento_id' => ['required', 'exists:equipamentos,id'],
            'descricao'      => ['required', 'string'],
            'data_prevista'  => ['nullable', 'date'],
        ]);

        ManutencaoPreventiva::create($data);

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva incluída com sucesso!');
    }

     public function show(ManutencaoPreventiva $manutencaoPreventiva)
    {
        $manutencaoPreventiva->load('equipamento.setor');

        return view('manutencoes.preventivas.show', [
            'manutencao' => $manutencaoPreventiva,
        ]);
    }

    public function edit(ManutencaoPreventiva $manutencaoPreventiva)
    {
        $equipamentos = Equipamento::orderBy('nome')->get();

        return view('manutencoes.preventivas.edit', [
            'manutencao'  => $manutencaoPreventiva,
            'equipamentos'=> $equipamentos,
        ]);
    }

    public function update(Request $request, ManutencaoPreventiva $manutencaoPreventiva)
    {
        $data = $request->validate([
            'equipamento_id' => ['required', 'exists:equipamentos,id'],
            'descricao'      => ['required', 'string'],
            'data_prevista'  => ['nullable', 'date'],
            'status'         => ['nullable', 'string', 'in:pendente,concluida'],
        ]);

        $manutencaoPreventiva->update($data);

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva atualizada com sucesso!');
    }

    public function concluir(ManutencaoPreventiva $manutencaoPreventiva)
    {
        $manutencaoPreventiva->update([
            'status' => 'concluida',
        ]);

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva marcada como concluída!');
    }

    public function destroy(ManutencaoPreventiva $manutencaoPreventiva)
    {
        $manutencaoPreventiva->delete();

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva excluída com sucesso!');
    }
}
