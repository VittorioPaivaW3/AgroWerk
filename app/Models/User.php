<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\OrdemServico;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'valor_hora',
    ];

        protected $casts = [
        'email_verified_at' => 'datetime',
        'valor_hora'        => 'decimal:2', // ou 'float' se preferir
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ordensComoTecnico()
    {
        return $this->belongsToMany(OrdemServico::class, 'ordem_servico_user')
            ->wherePivot('papel', 'tecnico')
            ->withTimestamps();
    }

    public function ordensComoGestor()
    {
        return $this->belongsToMany(OrdemServico::class, 'ordem_servico_user')
            ->wherePivot('papel', 'gestor')
            ->withTimestamps();
    }
}
