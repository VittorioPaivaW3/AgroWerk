<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nome',
        'cor',
        'setor_id',
        'status',
        'campos_extras',
        'observacoes',
        'manutencao_preventiva',
    ];

    protected $casts = [
        'manutencao_preventiva' => 'date',
        'campos_extras' => 'array',
    ];

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function manutencoesPreventivas()
    {
        return $this->hasMany(ManutencaoPreventiva::class);
    }
}
