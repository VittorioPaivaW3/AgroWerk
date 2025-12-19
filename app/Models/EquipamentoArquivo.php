<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipamentoArquivo extends Model
{
    protected $table = 'equipamento_arquivos';

    protected $fillable = [
        'equipamento_id',
        'path',
        'nome_original',
        'mime_type',
        'size',
    ];

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }
}
