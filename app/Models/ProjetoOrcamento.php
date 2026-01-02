<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjetoOrcamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'projeto_id',
        'path',
        'nome_original',
        'mime_type',
        'size',
    ];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
