<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManutencaoPreventiva extends Model
{
    protected $table = 'manutencoes_preventivas';

    protected $fillable = [
        'equipamento_id',
        'descricao',
        'data_prevista',
        'status',
    ];

    protected $casts = [
        'data_prevista' => 'date',
    ];

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }
}
