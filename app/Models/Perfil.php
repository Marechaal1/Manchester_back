<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Perfil extends Model
{
    protected $table = 'perfis';
    
    protected $fillable = [
        'nome_perfil',
        'descricao',
        'permissoes',
        'ativo'
    ];

    protected $casts = [
        'permissoes' => 'array',
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento muitos para muitos com usuários
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_perfil', 'perfil_id', 'usuario_id')
                    ->using(UsuarioPerfil::class)
                    ->withPivot(['data_atribuicao', 'data_remocao', 'ativo'])
                    ->withTimestamps();
    }
}
