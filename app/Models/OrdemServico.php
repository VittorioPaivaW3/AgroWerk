<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    // Se quiser definir quais campos podem ser preenchidos em massa:
    protected $fillable = [
        'cliente_id',
        'status',
        'descricao',
        'previsao_conclusao',
        // outros campos que você tiver
    ];

    // Relacionamento com Cliente (se existir)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
