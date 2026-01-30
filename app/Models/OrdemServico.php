<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servicos';

    protected $fillable = [
        'solicitante_id',
        'setor_id',
        'equipamento_id',
        'tipo',
        'prioridade',
        'descricao',
        'observacao_conclusao',
        'status',
        'previsao_conclusao',
        'inicio_execucao_em',
        'fim_execucao_em',
        'pausada_em',
        'total_minutos_pausa',
        'observacao_pausa',
        'custo_total',
        'concluida_por_terceiros',
    ];

    protected $casts = [
        'inicio_execucao_em'      => 'datetime',
        'fim_execucao_em'         => 'datetime',
        'pausada_em'              => 'datetime',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
        'custo_total'             => 'decimal:2',
        'total_minutos_pausa'     => 'integer',
        'concluida_por_terceiros' => 'boolean',
    ];

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function anexos()
    {
        return $this->hasMany(OrdemServicoAnexo::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'ordem_servico_user')
            ->withPivot('papel')
            ->withTimestamps();
    }

    /**
     * Técnicos atribuídos à OS.
     */
    public function tecnicos()
    {
        return $this->usuarios()->wherePivot('papel', 'tecnico');
    }

    /**
     * Gestores atribuídos à OS.
     */
    public function gestores()
    {
        return $this->usuarios()->wherePivot('papel', 'gestor');
    }

    public function scopeDoTecnico($query, $userId)
    {
        return $query->whereHas('tecnicos', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
        
    // =========================
    //  CÁLCULOS DE MÃO DE OBRA
    // =========================

    public function getDuracaoExecucaoEmHorasAttribute(): ?float
    {
        if (! $this->inicio_execucao_em || ! $this->fim_execucao_em) {
            return null;
        }

        $segundosBrutos = $this->inicio_execucao_em->diffInSeconds($this->fim_execucao_em);
        $segundosPausa  = ((int) ($this->total_minutos_pausa ?? 0)) * 60;
        $segundosAtivos = max($segundosBrutos - $segundosPausa, 0);

        return $segundosAtivos / 3600;
    }

    public function getCustoMaoDeObraAttribute(): ?float
    {
        $horas = $this->duracao_execucao_em_horas;

        if ($horas === null) {
            return null;
        }

        // Soma custo de cada técnico (horas * valor_hora)
        return $this->tecnicos->sum(function ($tec) use ($horas) {
            $valorHora = $tec->valor_hora ?? 0;

            return $valorHora * $horas;
        });
    }
}
