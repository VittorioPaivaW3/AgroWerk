<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemServicoAnexo extends Model
{
    protected $fillable = [
        'ordem_servico_id',
        'path',
        'nome_original',
        'mime_type',
        'size',
        'is_conclusao',
    ];

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class);
    }
}
