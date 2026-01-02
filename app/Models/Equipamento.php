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
        'terceiro',
    ];

    protected $casts = [
        'manutencao_preventiva' => 'date',
        'campos_extras' => 'array',
        'terceiro' => 'boolean',
    ];

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function manutencoesPreventivas()
    {
        return $this->hasMany(ManutencaoPreventiva::class);
    }
    public function arquivos()
    {
        return $this->hasMany(EquipamentoArquivo::class);
    }
}
