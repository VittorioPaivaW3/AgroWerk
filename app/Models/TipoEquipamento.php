<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEquipamento extends Model
{
    use HasFactory;

    protected $table = 'tipos_equipamento';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
    ];

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class, 'tipo_equipamento_id');
    }
}

