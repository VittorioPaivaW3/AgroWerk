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
        'tipo_equipamento_id',
        'status',
        'vida_util_h',
        'horimetro',
        'tem_horimetro',
        'foto_perfil',
        'campos_extras',
        'observacoes',
        'manutencao_preventiva',
        'terceiro',
    ];

    protected $casts = [
        'manutencao_preventiva' => 'date',
        'campos_extras' => 'array',
        'terceiro' => 'boolean',
        'tem_horimetro' => 'boolean',
        'vida_util_h' => 'integer',
        'horimetro' => 'decimal:2',
    ];

    public function setor()
    {
        return $this->belongsTo(Setor::class);
    }

    public function tipoEquipamento()
    {
        return $this->belongsTo(TipoEquipamento::class, 'tipo_equipamento_id');
    }

    public function manutencoesPreventivas()
    {
        return $this->hasMany(ManutencaoPreventiva::class);
    }
    public function arquivos()
    {
        return $this->hasMany(EquipamentoArquivo::class);
    }

    public function alertas()
    {
        return $this->hasMany(ManutencaoAlerta::class);
    }
}
