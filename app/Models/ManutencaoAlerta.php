<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutencaoAlerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipamento_id',
        'nome',
        'mensagem',
        'tipo',
        'recorrente',
        'dias_recorrencia',
        'data_inicio_recorrencia',
        'data_alerta',
        'horimetro_alvo',
        'horimetro_intervalo',
        'horimetro_base',
        'horimetro_antecedencia',
        'last_sent_at',
        'ultimo_realizado_em',
        'ultimo_realizado_horimetro',
        'ativo',
    ];

    protected $casts = [
        'recorrente' => 'boolean',
        'ativo' => 'boolean',
        'data_alerta' => 'date',
        'data_inicio_recorrencia' => 'date',
        'horimetro_alvo' => 'decimal:2',
        'horimetro_intervalo' => 'decimal:2',
        'horimetro_base' => 'decimal:2',
        'horimetro_antecedencia' => 'decimal:2',
        'ultimo_realizado_horimetro' => 'decimal:2',
        'dias_recorrencia' => 'integer',
        'last_sent_at' => 'datetime',
        'ultimo_realizado_em' => 'datetime',
    ];

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function horimetroAlvoCalculado(): ?float
    {
        if ($this->tipo !== 'horimetro') {
            return null;
        }

        if ($this->horimetro_intervalo !== null) {
            return (float) ($this->horimetro_base ?? 0) + (float) $this->horimetro_intervalo;
        }

        return $this->horimetro_alvo !== null ? (float) $this->horimetro_alvo : null;
    }

    public function horasRestantesHorimetro(?float $horimetroAtual = null): ?float
    {
        $alvo = $this->horimetroAlvoCalculado();

        if ($alvo === null) {
            return null;
        }

        $atual = $horimetroAtual ?? (float) ($this->equipamento?->horimetro ?? 0);

        return $alvo - $atual;
    }

    public function progressoHorimetro(?float $horimetroAtual = null): ?float
    {
        if ($this->tipo !== 'horimetro') {
            return null;
        }

        $atual = $horimetroAtual ?? (float) ($this->equipamento?->horimetro ?? 0);

        if ($this->horimetro_intervalo !== null && (float) $this->horimetro_intervalo > 0) {
            $base = (float) ($this->horimetro_base ?? 0);
            $usado = max(0, $atual - $base);
            $percentual = ($usado / (float) $this->horimetro_intervalo) * 100;

            return min(100, max(0, $percentual));
        }

        $alvo = $this->horimetro_alvo !== null ? (float) $this->horimetro_alvo : null;
        if (! $alvo || $alvo <= 0) {
            return null;
        }

        return min(100, max(0, ($atual / $alvo) * 100));
    }

    public function statusHorimetro(?float $horimetroAtual = null): string
    {
        $restantes = $this->horasRestantesHorimetro($horimetroAtual);

        if ($restantes === null) {
            return 'sem_dados';
        }

        if ($restantes <= 0) {
            return 'vencido';
        }

        if ($restantes <= (float) ($this->horimetro_antecedencia ?? 10)) {
            return 'critico';
        }

        return 'em_dia';
    }

    public function resumoHorimetro(?float $horimetroAtual = null): array
    {
        $atual = $horimetroAtual ?? (float) ($this->equipamento?->horimetro ?? 0);
        $alvo = $this->horimetroAlvoCalculado();
        $restantes = $this->horasRestantesHorimetro($atual);
        $progresso = $this->progressoHorimetro($atual);
        $status = $this->statusHorimetro($atual);

        return [
            'id' => $this->id,
            'equipamento_id' => $this->equipamento_id,
            'equipamento_nome' => $this->equipamento?->nome,
            'equipamento_codigo' => $this->equipamento?->codigo ?? $this->equipamento_id,
            'setor_nome' => $this->equipamento?->setor?->nome,
            'nome' => $this->nome ?: ($this->mensagem ?: 'Manutenção por horímetro'),
            'mensagem' => $this->mensagem,
            'horimetro_atual' => $atual,
            'horimetro_base' => $this->horimetro_base !== null ? (float) $this->horimetro_base : null,
            'horimetro_alvo' => $alvo,
            'horimetro_intervalo' => $this->horimetro_intervalo !== null ? (float) $this->horimetro_intervalo : null,
            'horimetro_antecedencia' => $this->horimetro_antecedencia !== null ? (float) $this->horimetro_antecedencia : 10,
            'horas_restantes' => $restantes,
            'progresso' => $progresso !== null ? round($progresso, 1) : null,
            'status' => $status,
            'critico' => in_array($status, ['critico', 'vencido'], true),
            'ultimo_realizado_em' => $this->ultimo_realizado_em?->format('d/m/Y H:i'),
            'ultimo_realizado_horimetro' => $this->ultimo_realizado_horimetro !== null ? (float) $this->ultimo_realizado_horimetro : null,
        ];
    }

    public static function criticosHorimetro(int $limit = 5)
    {
        return static::with('equipamento.setor')
            ->where('tipo', 'horimetro')
            ->ativos()
            ->get()
            ->map(fn (self $alerta) => $alerta->resumoHorimetro())
            ->filter(fn (array $alerta) => $alerta['critico'])
            ->sortBy(fn (array $alerta) => $alerta['horas_restantes'] ?? PHP_FLOAT_MAX)
            ->take($limit)
            ->values();
    }
}
