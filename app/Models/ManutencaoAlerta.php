<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutencaoAlerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipamento_id',
        'mensagem',
        'tipo',
        'recorrente',
        'dias_recorrencia',
        'data_inicio_recorrencia',
        'data_alerta',
        'horimetro_alvo',
        'last_sent_at',
    ];

    protected $casts = [
        'recorrente' => 'boolean',
        'data_alerta' => 'date',
        'data_inicio_recorrencia' => 'date',
        'horimetro_alvo' => 'decimal:2',
        'dias_recorrencia' => 'integer',
        'last_sent_at' => 'datetime',
    ];

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }
}
