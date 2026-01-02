<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'setores_id',
        'titulo',
        'descricao',
        'prazo',
        'orcamento_previsto',
        'orcamento_real',
        'status',
    ];

    protected $casts = [
        'prazo' => 'date',
        'orcamento_previsto' => 'decimal:2',
        'orcamento_real'     => 'decimal:2',
    ];

    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setores_id');
    }

    public function orcamentos()
    {
        return $this->hasMany(ProjetoOrcamento::class);
    }
}
