<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\ManutencaoAlerta;
use App\Models\ManutencaoPreventiva;
use Illuminate\Http\Request;
use App\Models\Setor;
use Illuminate\Support\Carbon;

class ManutencaoPreventivaController extends Controller
{
    public function index(Request $request)
    {
        $equipamentos = Equipamento::with('setor')
            ->orderBy('nome')
            ->get();

        $setores = Setor::orderBy('nome')->get();

        $query = ManutencaoPreventiva::with(['equipamento.setor'])
            ->orderBy('data_prevista', 'asc');

        if ($request->filled('equipamento_id')) {
            $query->where('equipamento_id', $request->equipamento_id);
        }

        if ($request->filled('setor_id')) {
            $query->whereHas('equipamento', function ($q) use ($request) {
                $q->where('setor_id', $request->setor_id);
            });
        }

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
        })->values()->all();

        $alertasQuery = ManutencaoAlerta::with('equipamento')
            ->where('tipo', 'data');

        if ($startDate && $endDate) {
            $alertasQuery->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($qq) use ($endDate) {
                    $qq->where('recorrente', true)
                        ->whereDate('data_inicio_recorrencia', '<=', $endDate);
                })->orWhere(function ($qq) use ($startDate, $endDate) {
                    $qq->where('recorrente', false)
                        ->whereDate('data_alerta', '>=', $startDate)
                        ->whereDate('data_alerta', '<=', $endDate);
                });
            });
        }

        $alertas = $alertasQuery->get();
        $alertaEvents = [];

        foreach ($alertas as $alerta) {
            $equip = $alerta->equipamento;
            $title = 'Aviso: ' . ($equip?->nome ?? 'Equipamento');
            $url = $equip ? route('equipamentos.show', $equip) : null;

            if ($alerta->recorrente && $alerta->dias_recorrencia && $alerta->data_inicio_recorrencia) {
                $dias = max(1, (int) $alerta->dias_recorrencia);
                $inicio = Carbon::parse($alerta->data_inicio_recorrencia);

                $next = $inicio->copy()->addDays($dias);

                if ($startDate && $next->lt($startDate)) continue;
                if ($endDate && $next->gt($endDate)) continue;

                $alertaEvents[] = [
                    'id'    => 'alerta-' . $alerta->id,
                    'title' => $title,
                    'start' => $next->format('Y-m-d'),
                    'url'   => $url,
                    'backgroundColor' => '#dc2626',
                    'borderColor'     => '#b91c1c',
                    'textColor'       => '#ffffff',
                ];
            } elseif ($alerta->data_alerta) {
                $data = $alerta->data_alerta;
                if ($startDate && $data->lt($startDate)) continue;
                if ($endDate && $data->gt($endDate)) continue;

                $alertaEvents[] = [
                    'id'    => 'alerta-' . $alerta->id,
                    'title' => $title,
                    'start' => $data->format('Y-m-d'),
                    'url'   => $url,
                    'backgroundColor' => '#dc2626',
                    'borderColor'     => '#b91c1c',
                    'textColor'       => '#ffffff',
                ];
            }
        }

        return response()->json(array_merge($events, $alertaEvents));
    }

    public function store(Request $request)
    {
        $this->bloqueiaTecnico();

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
        $this->bloqueiaTecnico();

        $equipamentos = Equipamento::orderBy('nome')->get();

        return view('manutencoes.preventivas.edit', [
            'manutencao'  => $manutencaoPreventiva,
            'equipamentos'=> $equipamentos,
        ]);
    }

    public function update(Request $request, ManutencaoPreventiva $manutencaoPreventiva)
    {
        $this->bloqueiaTecnico();

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
        $this->bloqueiaTecnico();

        $manutencaoPreventiva->update([
            'status' => 'concluida',
        ]);

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva marcada como concluída!');
    }

    public function destroy(ManutencaoPreventiva $manutencaoPreventiva)
    {
        $this->bloqueiaTecnico();

        $manutencaoPreventiva->delete();

        return redirect()
            ->route('manutencoes.preventivas.index')
            ->with('success', 'Manutenção preventiva excluída com sucesso!');
    }

    protected function bloqueiaTecnico(): void
    {
        $user = auth()->user();

        if ($user?->hasRole('tecnico')) {
            abort(403, 'Técnicos apenas podem visualizar manutenções preventivas.');
        }
    }
}
