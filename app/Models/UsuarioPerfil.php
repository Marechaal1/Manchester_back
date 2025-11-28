<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioPerfil extends Pivot
{
    protected $table = 'usuario_perfil';

    protected $casts = [
        'data_atribuicao' => 'datetime',
        'data_remocao' => 'datetime',
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}





